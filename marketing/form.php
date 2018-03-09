<?php 
    session_start();

    $link = mysqli_connect('192.254.233.171', 'tsolan_petfood', 'T!yz;gea6WwR', 'tsolan_petfood', '3306');
    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    }
    

    $sql = "INSERT INTO `tsolan_petfood`.`user` (`id`, `name`, `email`, `post_code`, `created`) VALUES (" . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NULL") . ", ?, ?, ?, CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE name=?, email=?, post_code=?;";
    
    if ($stmt = $link->prepare($sql)) {
        $stmt->bind_param("ssssss", $_POST['name'], $_POST['email'], $_POST['post_code'], $_POST['name'], $_POST['email'], $_POST['post_code']);     // Bind variables in order
        $stmt->execute();                               // Execute query
    }
    
    if (!$stmt) {
        die('Error: ' . $link->error . "; " . $stmt-> error);
    }
    
    $stmt->close();
    
    if(!isset($_SESSION['user_id'])){
        $_SESSION['user_id'] = $user_id = $link->insert_id;
    }else{
        $user_id = $_SESSION['user_id'];
    }
    
    for($i = 0; $i < count($_POST['pet']); $i++){
        $sql = "INSERT INTO `tsolan_petfood`.`pet` (`id`, `name`, `type`, `weight`, `feed`, `user`) VALUES (" . (isset($_SESSION['pet_ids'][$i]) ? $_SESSION['pet_ids'][$i] : "NULL") . ", ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=?, type=?, weight=?, feed=?, user=?;";
        
        if ($stmt = $link->prepare($sql)) {
           $stmt->bind_param("ssdsissdsi", $_POST['pet_name'][$i], $_POST['pet_type'][$i], $_POST['pet_weight'][$i], $_POST['pet_food_type'][$i], $user_id, $_POST['pet_name'][$i], $_POST['pet_type'][$i], $_POST['pet_weight'][$i], $_POST['pet_food_type'][$i], $user_id); 
           $stmt->execute();
        }
        
        $stmt->close(); 
        
        if(!isset($_SESSION['pet_ids'][$i])){
            $_SESSION['pet_ids'][$i] = $link->insert_id;
        }
    }
    
    $link->close();
    
    foreach($_POST as $field){
        //check for empty fields
        if(gettype($field) == 'array'){
            foreach($field as $subfield){
                if($subfield == ''){
                    header("Location: index.php", TRUE, 307);
                }  
            }
        }
        elseif($field == ''){
            header("Location: index.php", TRUE, 307);
        }
        
        //check for formatting of specific fields
        
    } 
?>