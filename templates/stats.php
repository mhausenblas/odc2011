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
      <th>Last 7 days</th>
<?php for ($year = date('Y'); $year >= $first_year; $year--) { ?>
      <th><?php e($year); ?></th>
<?php } ?>
    </tr>
  </thead>
  <tbody>
<?php foreach ($data as $id => $details) { ?>
    <tr class="coordinates">
      <th class="council" rowspan="2"><?php e($councils[$id]['name']); ?>
<?php if (@$councils[$id]['system']) { ?>
        <small><br/><a href="<?php e($councils[$id]['website']); ?>">Planning website</a> (<?php e($councils[$id]['system']); ?>)</small>
<?php } ?>
      </th>
      <td><?php e($details['recent'][1]); ?></td>
<?php for ($year = date('Y'); $year >= $first_year; $year--) { ?>
      <td><?php e($details[$year][1]); ?></td>
<?php } ?>
    </tr>
    <tr class="no-coordinates">
      <td><?php e($details['recent'][0]); ?></td>
<?php for ($year = date('Y'); $year >= $first_year; $year--) { ?>
      <td><?php e($details[$year][0]); ?></td>
<?php } ?>
    </tr>
<?php } ?>
  </tbody>
</table>
