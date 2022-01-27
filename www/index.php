<html>
 <head>
  <title>Docker Lamp</title>

  <meta charset="utf-8"> 
  <!--
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
-->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.13.0/css/all.css"
      integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V"
      crossorigin="anonymous"
    />
</head>
<?php
include('config.php');
?>
<body>
    <div class="container">
        <h1><?php echo "Docker LAMP - linux|mariadb|php"; ?></h1>
        <h4><?php echo 'Ação: '. $_GET['modulo']. ' '; ?></h4>
        <h6><?php echo $msg; ?></h6>
        <?php
        if ($_SERVER['REQUEST_URI'] == '/'):
        ?>
        <table class="table table-dark table-striped">
            <thead><tr><th>Ação</th><th>ID</th><th>Nome</th></tr></thead>
            <?php
                $query = 'SELECT id, name From Person';
                foreach  ($conn->query($query) as $row) {
                    echo '<tr>';
                    echo '<td style="width: 10%;">'.
                    '<a href="/edit/'. $row["id"] . '" class="link-info"><i class="fas fa-edit"></i></a>'.
                    '  <a href="/delete/'. $row["id"] . '" class="link-danger"><i class="far fa-trash-alt"></i></a>'.
                    '</td>';
                    echo '<td style="width: 10%;">' . $row["id"] . '</td>';
                    echo '<td>' . $row["name"] . '</td>';
                    echo '</tr>';
                }
            ?>
        </table>
        <a href="/novo/ok">
                <button type="button" class="btn btn-success">Novo</button>
        </a>
        <?php
        # fim if home
        elseif ($_GET['modulo'] == 'novo'):
            
        ?>
        <form class="form-inline py-3 needs-validation" action="/insert/ok" method='POST'>
            <input type="text" name="name" class="form-control" required/>            
            <br>
            <input type="text" name="surname" class="form-control" required/>            
            <br>
            <button type="submit" class="btn btn-primary">Inserir</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /novo
        elseif ($_GET['modulo'] == 'insert' and $_POST['name']):
            $data = [
                'UPname' => $_POST['name'],
                'UPsurname' => $_POST['surname']
            ];
            $sql = "INSERT into Person (name, surname) VALUES (?, ?)";
            // echo $_POST['name'];
            $conn->prepare($sql)->execute(array($_POST['name'], $_POST['surname']));
        ?>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim if /update

        elseif ($_GET['modulo'] == 'edit' and is_numeric($_GET['id'])):
            $query = 'SELECT id, name From Person WHERE ID='.$_GET['id'].' LIMIT 1';
            $row = $conn->query($query)->fetch();
        ?>
        <form class="form-inline py-3 needs-validation" action="/update/ok" method='POST'>
            <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" required/>
            <input type='hidden' name='zapid' value='<?php echo $row["id"]; ?>'>
            <br>
            <button type="submit" class="btn btn-primary">Alterar</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /edit

        elseif ($_GET['modulo'] == 'update' and $_POST['name']):
            $data = [
                'UPname' => $_POST['name'],
                'UPid' => $_POST['zapid'],
            ];
            $sql = "UPDATE Person SET name=:UPname WHERE id=:UPid";
            // echo $_POST['name'];
            $conn->prepare($sql)->execute($data);
        ?>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim if /update
        elseif ($_GET['modulo'] == 'delete' and is_numeric($_GET['id'])):
            $query = 'SELECT id, name From Person WHERE ID='.$_GET['id'].' LIMIT 1';
            $row = $conn->query($query)->fetch();
        ?>
        <form class="form-inline py-3 needs-validation" action="/confdelete/ok" method='POST'>
            <input type="text" name="name" class="form-control" value="<?php echo $row["name"]; ?>" readonly/>
            <input type='hidden' name='zapid' value='<?php echo $row["id"]; ?>'>
            <br>
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /delete

        elseif ($_GET['modulo'] == 'confdelete' and $_POST['name']):
            $data = [
                'UPid' => $_POST['zapid'],
            ];
            $sql = "DELETE FROM Person WHERE id=:UPid LIMIT 1";
            // echo $_POST['name'];
            $conn->prepare($sql)->execute($data);
        ?>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim if /confdelete
        else: 
        ?>
        <img src=<?php echo 'http://'.$_SERVER['HTTP_HOST'].'/404.png'; ?>>  
        <br>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim lee
        endif
        ?>
    </div>
</body>
</html>