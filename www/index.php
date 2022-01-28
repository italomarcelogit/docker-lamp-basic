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
include('_mysetup.php');


try {        
    $conn = new PDO("mysql:host=$servername;dbname=".$database, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $msg = "Status: Connected successfully";
}
catch(PDOException $e) {
     $msg = "status: Connection failed: " . $e->getMessage();
}
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
            <thead><tr><th>Ação</th><th>Nome</th><th>Cargo</th></tr></thead>
            <?php
                $query = 'SELECT id, vnome, vcargo From Visitante';
                foreach  ($conn->query($query) as $row) {
                    echo '<tr>';
                    echo '<td style="width: 10%;">'.
                    '<a href="/edit/'. $row["id"] . '" class="link-info"><i class="fas fa-edit"></i></a>'.
                    '  <a href="/delete/'. $row["id"] . '" class="link-danger"><i class="far fa-trash-alt"></i></a>'.
                    '</td>';
                    echo '<td style="width: 30%;">' . $row["vnome"] . '</td>';
                    echo '<td>' . $row["vcargo"] . '</td>';
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
            <h6>Nome:</h6>
            <input type="text" name="nome" class="form-control" required/>            
            <br>
            <h6>Cargo:</h6>
            <input type="text" name="cargo" class="form-control" required/>            
            <br>
            <button type="submit" class="btn btn-primary">Inserir</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /novo
        elseif ($_GET['modulo'] == 'insert' and $_POST['nome']):            
            $sql = "INSERT into Visitante (vnome, vcargo) VALUES (?, ?)";
            // echo $_POST['name'];
            $conn->prepare($sql)->execute(array($_POST['nome'], $_POST['cargo']));
        ?>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim if /update

        elseif ($_GET['modulo'] == 'edit' and is_numeric($_GET['id'])):
            $query = 'SELECT id, vnome, vcargo From Visitante WHERE ID='.$_GET['id'].' LIMIT 1';
            $row = $conn->query($query)->fetch();
        ?>
        <form class="form-inline py-3 needs-validation" action="/update/ok" method='POST'>
            <h6>Nome:</h6>
            <input type="text" name="nome" class="form-control" value="<?php echo $row["vnome"]; ?>" required/>
            <br>
            <h6>Nome:</h6>
            <input type="text" name="cargo" class="form-control" value="<?php echo $row["vcargo"]; ?>" required/>
            <input type='hidden' name='zapid' value='<?php echo $row["id"]; ?>'>
            <br>
            <button type="submit" class="btn btn-primary">Alterar</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /edit

        elseif ($_GET['modulo'] == 'update' and $_POST['nome']):
            $data = [
                'UPname' => $_POST['nome'],
                'UPcargo' => $_POST['cargo'],
                'UPid' => $_POST['zapid'],
            ];
            $sql = "UPDATE Visitante SET vnome=:UPname, vcargo=:UPcargo  WHERE id=:UPid";
            // echo $_POST['name'];
            $conn->prepare($sql)->execute($data);
        ?>
        <a href="/">
                <button type="button" class="btn btn-success">Voltar</button>
        </a>
        <?php
        # fim if /update
        elseif ($_GET['modulo'] == 'delete' and is_numeric($_GET['id'])):
            $query = 'SELECT id, vnome, vcargo From Visitante WHERE ID='.$_GET['id'].' LIMIT 1';
            $row = $conn->query($query)->fetch();
        ?>
        <form class="form-inline py-3 needs-validation" action="/confdelete/ok" method='POST'>
            <h6>Nome:</h6>
            <input type="text" name="nome" class="form-control" value="<?php echo $row["vnome"]; ?>" readonly/>
            <br>
            <h6>Nome:</h6>
            <input type="text" name="cargo" class="form-control" value="<?php echo $row["vcargo"]; ?>" readonly/>
            <input type='hidden' name='zapid' value='<?php echo $row["id"]; ?>'>
            <br>
            <button type="submit" class="btn btn-danger">Excluir</button>
            <a href="/">
                <button type="button" class="btn btn-warning">Voltar</button>
            </a>
        </form>        
        <?php
            
        # fim if /delete

        elseif ($_GET['modulo'] == 'confdelete' and $_POST['nome']):
            $data = [
                'UPid' => $_POST['zapid'],
            ];
            $sql = "DELETE FROM Visitante WHERE id=:UPid LIMIT 1";
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