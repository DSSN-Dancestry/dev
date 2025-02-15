<?php
  require 'connect.php';

  $feat_id = $_POST['feat_id'];
  $feat_val = $_POST['feat_val'];
  $feat_user = $_POST['feat_user'];

  $conn = getDbConnection();

  $sql = "UPDATE admin_features SET feature_enabled='$feat_val', feature_updated_by='$feat_user', feature_updated_date = curdate() WHERE feature_id = '$feat_id'";

  $statement = $conn->prepare($sql);
  $statement->execute();

?>
