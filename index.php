
<?php
$insert=false;
$update=false;
$delete=false;

$servername="localhost";
$username="root";
$password="";
$database="notes";

$conn=mysqli_connect($servername,$username,$password,$database);

if(!$conn){
  die("SORRY! We coudn't connect ". mysqli_connect_error());
}
if(isset($_GET['delete'])){
  $sno=$_GET['delete'];
  $delete=true;
  $sql="Delete from `notes` where `slno`=$sno";
  $result=mysqli_query($conn,$sql);
}

if($_SERVER['REQUEST_METHOD']=="POST"){
  if(isset($_POST['snoEdit'])){
   //Update the record
   $sno=$_POST["snoEdit"];
   $title=$_POST["titleEdit"];
   $desc=$_POST["descEdit"];

    $sql="UPDATE `notes` SET `title`='$title' ,`description` = '$desc' WHERE `slno` = $sno;";
    $result=mysqli_query($conn,$sql);
    if($result){
     $update=true;
      }
       else{
        echo "Updation failed " . mysqli_error();;
         }
    
  }else{
      $title=$_POST["title"];
      $desc=$_POST["desc"];

      $sql="INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$desc')";
      $result=mysqli_query($conn,$sql);

      if($result){
      $insert=true;
          }
          else{
            echo "failed to add note" . mysqli_error();;
              }
        }
}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    
    <link rel="stylesheet" href="//cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">

    <title>iNotes - Notes taking made easy</title>

  </head>


  <body>


<!-- edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit this note</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form action="/CRUD/index.php" method="POST">
  <div class="modal-body">
      <input type="hidden" name="snoEdit" id="snoEdit">
      <div class="mb-3">
        <label for="titleEdit" class="form-label">Note Title</label>
        <input type="text" class="form-control" id="titleEdit" name="titleEdit" aria-describedby="emailHelp">
      </div>
     <div class="mb-3">
        <label for="descEdit" class="form-label">Note Description</label>
        <textarea class="form-control" id="descEdit" rows="3" name="descEdit"></textarea>
      </div>
      
    </div>
    
    <div class="modal-footer d-block mr-auto">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Save changes</button>
    </div>
  </form>

    </div>
  </div>
</div>



<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"> <img src="/CRUD/phplogo3.png" alt="" height="30px" ">  iNotes</a>
 <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="#">Home</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">About</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="#">Contact us</a>
        </li>
        
      </ul>
      <form class="d-flex">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
      </form>
    </div>
  </div>
</nav>

<?php
if($insert){
 echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
 <strong>SUCCESS! </strong> Your note added successfully
 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>';
 }
?>
<?php
if($update){
 echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
 <strong>SUCCESS! </strong> Your note updated successfully
 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>';
 }
?>
<?php
if($delete){
 echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
 <strong>SUCCESS! </strong> Your note deleted successfully
 <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
     </div>';
 }
?>


<div class="container mt-3">
<h2>Add a Note to iNotes</h2>
<form action="/CRUD/index.php" method="POST">
  <div class="mb-3">
     <label for="title" class="form-label">Note Title</label>   
     <input type="text" class="form-control" id="title" name="title" aria-describedby="emailHelp">
  </div>
 <div class="mb-3">
  <label for="desc" class="form-label">Note Description</label>
  <textarea class="form-control" id="desc" rows="3" name="desc"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Add Note</button>
</form>
</div>

<div class="container my-4">
<table class="table" id="myTable">
  <thead>
    <tr>
      <th scope="col">Slno</th>
      <th scope="col">Title</th>
      <th scope="col">Description</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody>
  <?php
   $sql="SELECT * FROM `notes`";
   $result=mysqli_query($conn,$sql);
   
   $sno=0;
   while($row=mysqli_fetch_assoc($result)){
     $sno=$sno+1;
    echo "<tr>
             <th scope='row'> ".$sno."</th>
             <td>".$row['title'] ."</td>
             <td>".$row['description']."</td>
             <td><button class= 'edit btn btn-sm btn-primary'id=".$row['slno']." >Edit</button> 
                 <button class= 'delete btn btn-sm btn-primary'id=d".$row['slno']." >Delete</button></td>
         </tr>"; 
  }
 
  
  ?>
    
  </tbody>
</table>
</div>

<hr> 













    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
   
   

    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script> 
    
    <script src="//cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    
    <script>
    let table = new DataTable('#myTable');
    </script>

    <script>
      edits=document.getElementsByClassName('edit');
      Array.from(edits).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          console.log("edit");
          tr=e.target.parentNode.parentNode;     //when event occurs.... what is the target? the table row
          title=tr.getElementsByTagName("td")[0].innerText; //1st td(table data)
          description=tr.getElementsByTagName("td")[1].innerText; //1nd td(table data)
          
          console.log(title,description);
          titleEdit.value=title;
          descEdit.value=description;
          snoEdit.value=e.target.id;
          console.log(e.target.id);

          //to trigger modal from JS
        $('#editModal').modal('toggle');
        })
      })

      deletes=document.getElementsByClassName('delete');
      Array.from(deletes).forEach((element)=>{
        element.addEventListener("click",(e)=>{
          console.log("edit",);
          sno=e.target.id.substr(1,);     //when event occurs.... what is the target? the table row
          
          
          if(confirm("Are you sure you want to delete this note!")){
            console.log("yes");
            window.location=`/CRUD/index.php?delete=${sno}`;
           //TODO: create a form and use post request to submit the form---->for security(because any one can hack delete my note(security loop holes))


          }else{
            console.log("no");
          }
        })
      })
    </script>
   

  </body>
</html>