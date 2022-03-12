<?php 

include 'config.php';

// handles edits
if (isset($_POST['cat'])) {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $descr = $_POST['descr'];
    $cat = $_POST['cat'];

    
 	$stmt = $con->prepare("UPDATE websites SET title = ?, url = ?, descr = ?, category = ? WHERE id = ?");
     $stmt->bind_param("sssss", $title, $url, $descr, $cat, $id);
     $stmt->execute();
     $result = $stmt->get_result();
     $stmt->close();
     
    // this generates the JSON file
    $rows = array();
    $sql = ("SELECT id, title, url, descr, category FROM websites WHERE pending = 0");
    mysqli_set_charset($con, 'utf8');
    if ($result = mysqli_query($con, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        $f = fopen('yesterlinks.json', 'w');
        fwrite($f, json_encode($rows));
        }
    }
}

// handles deletions
if (isset($_POST['del'])) {
    $id = $_POST['id'];
    $stmt = $con->prepare("DELETE FROM websites WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    
    // this generates the JSON file
    $rows = array();
    $sql = ("SELECT id, title, url, descr, category FROM websites WHERE pending = 0");
    mysqli_set_charset($con, 'utf8');
    if ($result = mysqli_query($con, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        $f = fopen('yesterlinks.json', 'w');
        fwrite($f, json_encode($rows));
        }
    }
}

// handles submissions
if (isset($_POST['submit'])) {
    $honeypot = $_POST['honeypot'];
        if(!empty($honeypot)){
            return; //you may add code here to echo an error etc.
        }else{
            $title = $_POST['titleInput'];
            $url = $_POST['urlInput'];
            $descr = $_POST['descrInput'];
            $cat = $_POST['categories'];
            echo $title;
            echo $url;
            echo $descr;
            echo $cat;
            $stmt = $con->prepare("INSERT INTO websites(title, url, descr, category) VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $title, $url, $descr, $cat);
            $stmt->execute();
            $stmt->close();
            header('location: submit-a-link.php');
        }

}

  // this block handles approvals logic
  if (isset($_POST['approved'])) {
    $id = $_POST['id'];
    $stmt = $con->prepare("UPDATE websites SET pending = 0 WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->close();
    
        // this generates the JSON file
    $rows = array();
    $sql = ("SELECT id, title, url, descr, category FROM websites WHERE pending = 0");
    mysqli_set_charset($con, 'utf8');
    if ($result = mysqli_query($con, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $rows[] = $row;
            }
        $f = fopen('yesterlinks.json', 'w');
        fwrite($f, json_encode($rows));
        }
    }
}