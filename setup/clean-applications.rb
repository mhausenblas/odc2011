#!/usr/bin/ruby

require 'iconv'
require 'csv'
require 'digest/sha1'

if ARGV.length != 1 then
  puts "Usage: ./clean-applications.rb GPlan_Metadata.txt > applications.csv"
  exit
end

filename = ARGV[0]

r = %r{
  ^
  (\d+),             #  1 id -- a simple auto-increment integer
  ([a-zA-Z0-9.]+),   #  2 file_number -- usually a number, possibly with leading zeros, but sometimes contains letters or punctuation too
  (-?\d*(?:\.\d*)?), #  3 lat_lo -- site bounding box, smaller latitude
  (-?\d*(?:\.\d*)?), #  4 lng_lo -- site bounding box, smaller longitude
  (-?\d*(?:\.\d*)?), #  5 lat_hi -- site bounding box, larger latitude
  (-?\d*(?:\.\d*)?), #  6 lng_hi -- site bounding box, larger longitude
  (-?\d*\.\d*\       #  7 coords -- site polygon; a sequence of lat-long pairs;
   -?\d*\.\d*\       #           the two numbers in each pair are separated by
   (?:,?             #           a space, and the different points are separated
    -?\d*\.\d*\      #           by space-comma (' ,') except in rare cases just space
    -?\d*\.\d*\ )*   #           The coords field is sometimes empty.
  )?,
                     #  8/11 name1
                     #  9/12 name2
                     # 10/13 name3
  (?:                        # We have two alternatives for parsing the address.
    \ *((?:[^,]|,\ )*,?)\ *, # Usually, full name is trim(name1) + ' ' + trim(name2),
    \ *((?:[^,]|,\ )*,?)\ *, # perhaps with some extra characters that did not fit into the name1 and name2 DB columns
    (\8\ \9.*?)
  |
    ((?:[^,]|,\ )*,?)\ *,    # Sometimes, name3 is independent from the others. We allow
    ((?:[^,]|,\ )*,?)\ *,    # commas only at the end, and inside if followed by a space.
    ((?:[^,]|,\ )*,?)        # This works *almost* all the time.
  ),
  (\d\d\d\d-\d\d-\d\d\ \d\d:\d\d:\d\d),   # 14 received_date -- can be empty in apps that don't have *any* info except geometry @@@ can be 1900 or other rubbish date?
  (\d\d\d\d-\d\d-\d\d\ \d\d:\d\d:\d\d)?,  # 15 decision_date -- can be empty @@@ can be 1900 or other rubbish date?
  # the splitting into four address fields is quiet heuristically ...
  ((?:[^,]|,[\ 0-9]|[0-9],)*,*),          # 16 address1 -- allow commas if followed by a space, or followed or preceded by digits, or at the end
  (.*?,*),                                # 17 address2 -- allow any number of commas here
  ((?:[^,]|,[\ 0-9])*,*),                 # 18 address3 -- allow commas if followed by a space or digit, or at the end
  ([A-Z0-9/, .]+|(?:[^,]|,[\ 0-9])+,*),   # 19 address4 -- allow commas if followed by a space or digit, or at the end, or it it's all uppercase
  ([CNRU]),          # 20 dec_code
  (\d+),             # 21 status_code
  (.*?),             # 22 development_description
  (CONDITIONAL|UNCONDITIONAL|REFUSED)?,
                     # 23 decision_code
  (APPLICATION\ FINALISED|DEEMED\ WITHDRAWN|FURTHER\ INFORMATION|INCOMPLETED\ APPLICATION|NEW\ APPLICATION|PRE-VALIDATION|WITHDRAWN|APPEALED|APPEALED\ FINANCIAL|DECISION\ MADE|LEAVE\ TO\ APPEAL)?,
                     # 24 application_status
  (\d+),             # 25 county
  (\d{5,9}|U),       # 26 townland
  (\d+)              # 27 authority
  \r$
}xm

decision_codes = Hash['C' => 'CONDITIONAL', 'U' => 'UNCONDITIONAL', 'R' => 'REFUSED', 'N' => nil]

conv = Iconv.new('UTF-8','LATIN1')

file = File.new(filename, "r")
skipped = []
applicationHashes = {}
csv = CSV::Writer.generate($stdout)
csv << [
    'file_number', 'authority', 'lat', 'lng',
    'name1', 'name2', 'name3',
    'received_date', 'decision_date',
    'address1', 'address2', 'address3', 'address4',
    'decision', 'status', 'description']
while line = file.gets
  while (!(match = r.match(line)))
    # The regexp should match everything except lines that
    # are incomplete because the description contained a
    # newline.
    if /,\d+\r$/ =~ line
      skipped << line
      break
    end
    # So let's assume that the description just continues on
    # the next line.
    next_line = file.gets
    # Oops, next line looks like a normal record, not like
    # more description, so actually the first line really
    # doesn't match our regexp
    fail "Parse failure: " + line if /^\d+,/ =~ next_line   
    # Ok, just append the next line and try again if this
    # completes the record
    line += next_line
  end
  next if !match  # skip lines that couldn't be parsed
  data = [nil] + match[1..27]
  # Latin1 => UTF-8
  (1..27).each do |i|
    data[i] = conv.iconv(data[i]) if /[^\x00-\x7F]/ =~ data[i]
    data[i] = nil if data[i] and data[i].empty?
  end
  # We assume that dec_code and decision_code are effectively
  # the same thing; check this to be on the safe side
  fail "Decision code failure: " + line if decision_codes[data[20]] != data[23]
  data[14] = nil if data[14] and data[14][0..3] == '1900'
  data[15] = nil if data[15] and data[15][0..3] == '1900'
  ((8..13).to_a + (16..19).to_a).each do |i|
    data[i].gsub!(/,+$/, '') if data[i]
  end
  hash = Digest::SHA1.hexdigest(data[2].to_s + ((data[3].to_f + data[5].to_f) / 2).to_s + ((data[4].to_f + data[6].to_f) / 2).to_s + ((data[8] or data[11])).to_s + ((data[9] or data[12])).to_s + ((data[10] or data[13])).to_s + (data[14] ? data[14][0..9] : nil).to_s + (data[15] ? data[15][0..9] : nil).to_s + data[16].to_s + data[17].to_s + data[18].to_s + data[19].to_s + data[20].to_s + data[23].to_s + data[27].to_s + data[22].to_s)
  if applicationHashes[hash] == nil
    applicationHashes[hash] = true
#    $stderr.puts hash + '  ' + '1'
    lat = (data[3].to_f + data[5].to_f) / 2
    lng = (data[4].to_f + data[6].to_f) / 2
    lat = nil if lat and lat == 0
    lng = nil if lng and lng == 0
    csv << [
      data[2], data[27], lat, lng,
      (data[8] or data[11]), (data[9] or data[12]), (data[10] or data[13]),
      data[14] ? data[14][0..9] : nil, data[15] ? data[15][0..9] : nil,
      data[16] ? data[16].strip : nil, data[17] ? data[17].strip : nil, data[18] ? data[18].strip : nil, data[19] ? data[19].strip : nil,
      data[20], data[21], data[22]]
  else
#    $stderr.puts hash + '  ' + '0'
  end
end
$stderr.puts '# Skipped ' + skipped.length.to_s + ' unparseable lines'
