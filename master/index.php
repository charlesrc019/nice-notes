<?php

$msgfile = 'messages.txt';
$fhndl = fopen($msgfile, "r");
$messages = fread($fhndl,filesize($msgfile));
fclose($fhndl);

$filename = '../members.csv';
$members = []; 
if (($h = fopen("{$filename}", "r")) !== FALSE) 
{
  $i = 0;
  while (($data = fgetcsv($h, 1000, ",")) !== FALSE) 
  {
    $members[$i] = $data;
    $i++;
  }
  fclose($h);
}

?>

<!doctype html>
<html>

  <head>
    <title>Master | [ORG_NAME]</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
  </head>

  <body>
    <div class="container">

      <div class="jumbotron mt-4 px-4 pb-1 pt-3">
        <h1>Nice Notes Master</h1>
        <p class="lead">[ORG_NAME]</p>
      </div>
      <?php 
        if ($_GET['sent'] == "true") {
          echo('<div class="alert alert-success mt-3" role="alert">Nice note <strong>sent</strong>!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
        }
        elseif ( !isset($_GET['sent']) ) {
          echo('');
        }
        else {
          $dst = strtoupper($_GET['sent']);
          echo("<div class='alert alert-danger mt-3' role='alert'><strong>Error</strong> sending nice note to $dst.<button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>");
        }
      ?>
      <div class="mt-4 mb-4">
        <form action="main.php" method="post">
          <div class="form-group">
            <label for="to">Recipients</label>
            <br />
            <select name="dst[]" class="form-control selectpicker" id="to" multiple data-live-search="true" data-width="css-width">
<?php
  for ($j = 0; $j < $i; $j++) {
    echo('              <option value="+' . $members[$j][1] . '" data-tokens="' . strtolower($members[$j][0]) . '">' . $members[$j][0] . "</option>\n");
  }
?>
            </select>
          </div>
          <div class="form-group">
            <label for="messages">Message Options</label>
            <textarea name="msgs" class="form-control" id="messages" rows="50"><?php echo $messages; ?></textarea>
            <p><small>One message per line. No blank lines.</small></p>
            <input type="checkbox" id="opt" name="opt" value="true">
            <label for="opt">Save this message list?</label>
          </div>
          <button type="submit" class="form-control btn btn-primary" id="submit">Send Random Messages</button>
        </form>
      </div>
      
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
  
  </body>
  
</html>
