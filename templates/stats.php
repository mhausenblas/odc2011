<p>This table shows the number of planning applications that we have
  collected in our databases, broken down by planning authority and 
  year.<p>
<p><strong>Green values</strong> indicate planning applications that
  have been geocoded by the planning authority and can be shown on a map.
  <strong>Red values</strong> indicate planning applications
  that have not been geocoded and are therefore not shown on this site.</p>
<p><strong>Empty cells</strong> indicate that we have no applications for the
  given period.</p>

<table class="stats">
  <thead>
    <tr>
      <th>County / Year</th>
<?php for ($year = $first_year; $year <= date('Y'); $year++) { ?>
      <th><?php e($year); ?></th>
<?php } ?>
      <th>Last 7 days</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data as $council => $details) { ?>
    <tr class="coordinates">
      <th rowspan="2"><?php e($council); ?></th>
<?php for ($year = $first_year; $year <= date('Y'); $year++) { ?>
      <td><?php e($details[$year][1]); ?></td>
<?php } ?>
      <td><?php e($details['recent'][1]); ?></td>
    </tr>
    <tr class="no-coordinates">
<?php for ($year = $first_year; $year <= date('Y'); $year++) { ?>
      <td><?php e($details[$year][0]); ?></td>
<?php } ?>
      <td><?php e($details['recent'][0]); ?></td>
    </tr>
<?php } ?>
  </tbody>
</table>
