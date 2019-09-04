<?php require './user-only.inc.php'; ?>

<?php 
require_once './Components.class.php';

$component = new Component();
$components = $component->allComponents();

?>

<?php include './header.layout.php'; ?>

<h4>Component Database</h4>


<table class="table mt-5">

  <thead>
    <tr>
      <th>Name</th>
      <th>ID</th>
    </tr>
  </thead>

  <tbody>
  <?php foreach($components as $component) { ?>
    <tr>
      <td><?php echo "$component->name"; ?></td>
      <th><?php echo "$component->id"; ?></th>
    </tr>
  <?php } ?>
    
  </tbody>

</table>

<div class="row">
  <div class="col-md-12 mt-3 mb-3">
  <a href="#top"><button class="btn btn-dark float-right">Back to top</button></a>
  </div>
</div>

<?php include './footer.layout.php'; ?>