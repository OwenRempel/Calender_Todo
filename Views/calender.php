<?php


if(isset($page_query[1]) and !empty($page_query[1])){
   echo "<a href='/calender/' class='btn blue darken-3'>Back</a>";
}else{
    echo "<a href='/' class='btn blue darken-3'>Back</a>";
}

class Calender{
    public static function create(){
        $DB = new Database;
        if(isset($_POST['Cal_add'])){
            $userCheck = $DB->query("SELECT Name FROM Calenders WHERE Name = :name", array('name'=>$_POST['Name']));
        
            if(!isset($userCheck[0])){
                 $DB->query('INSERT INTO Calenders (Name, Comment) VALUES (:name, :comment)', array(
                    'name'=>$_POST['Name'],
                    'comment'=>$_POST['Comment']
                ));
                $_SESSION['Notify'] = 'Calender Added';
                $_SESSION['Notify_error'] = 0;
            }else{
                $_SESSION['Notify'] = 'That Calender already exists';
                $_SESSION['Notify_error'] = 1;
            }
            header('location:'.$_SERVER['REDIRECT_URL']);
        }

        ?>
        <h3>Add Calender</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <input type="text" name="Name" >
                    <label for="Name">Name</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Comment" >
                    <label for="Phone">Comment</label>
                </div>
                <button class="btn blue darken-3" type="submit" name="Cal_add" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function update($ID){
        $DB = new Database;
        
        if(isset($_POST['Cal_up'])){
            $userCheck = $DB->query("SELECT Name, ID FROM Calenders WHERE Name = :name", array('name'=>$_POST['Name']));
            if(!isset($userCheck[0]) or $userCheck[0]['ID'] == $_POST['ID']){
                $send_check = $DB->query('UPDATE Calenders SET Name=:name, Comment=:comment WHERE ID=:id',array(
                    'name'=>$_POST['Name'],
                    'comment'=>$_POST['Comment'],
                    'id'=>$_POST['ID']
                ));
                $_SESSION['Notify'] = 'Calender Updated';
                $_SESSION['Notify_error'] = 0;
            }else{
                $_SESSION['Notify'] = 'That Calender already exists';
                $_SESSION['Notify_error'] = 1;
            }
            header('location:/calender');
        }
        
        $data = $DB->query('SELECT Name, Comment FROM Calenders WHERE ID=:id', array('id'=>$ID));
        if(!isset($data[0])){
            $_SESSION['Notify'] = '404 Please Enter a valid ID';
            $_SESSION['Notify_error'] = 1;
            header('location:/calender');
        }

        ?>
        <h3>Update Calender</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <input type="text" name="Name" value='<?php echo $data[0]['Name'];?>''>
                    <label for="Name">Name</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Comment" value="<?php echo $data[0]['Comment'];?>">
                    <label for="Phone">Comment</label>
                </div>
                <input type="hidden" name="ID" value="<?php echo $ID;?>">
                <button class="btn blue darken-3" type="submit" name="Cal_up" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function delete($ID){
        $DB = new Database;
        if(isset($_POST['Cal_del'])){
            $DB->query('DELETE FROM Calenders WHERE ID=:id',array('id'=>$_POST['ID']));
            $_SESSION['Notify'] = 'Calender Deleted';
            $_SESSION['Notify_error'] = 0;
            header('location:/calender');
        }
       ?>
            <form action="" method="post">
                <h5 class="deleteBack">Are you sure that you want to delete this Calender?</h5>
                <input type="hidden" name="ID" value='<?php echo $ID;?>'>
                <button class="btn red darken-3" type="submit" name="Cal_del" value='1'>Submit
            </form>
       <?php
    }
    public static function list(){
        $DB = new Database;
        $list_data = $DB->query('SELECT Name, Comment, Adate, ID from Calenders');
       
        ?>
         <a href='/calender/create' class="btn blue darken-3">Add</a>
        <div class="list">
                <?php
                    foreach($list_data as $row){
                                    
                    ?>
                        <div class="list-item small">
                            <span class="name"><?php echo $row['Name'];?></span>
                            <span class="adate"> <?php echo date('M d', strtotime($row['Adate']));?></span>
                            
                            
                            <span class="moreinfo hide">
                                <span>
                                <?php echo $row['Comment'];?>
                                </span>
                                
                                
                            </span>
                            <span class="list-item-actions hide">
                            <a href='/calender/update/<?php echo $row['ID'];?>' class="btn blue darken-3">Edit</a>
                            <a href='/calender/delete/<?php echo $row['ID'];?>' class="btn red darken-3">Delete</a>
                            </span>
                        </div>
                    <?php
                    }
                ?>
            </div>
            
           
            
        <?php
       
    }
}
$calender = new Calender;
if(isset($page_query[1]) and $page_query[1] == 'create'){
    $calender->create();

}elseif(isset($page_query[1]) and is_valid_action($page_query[1]) and isset($page_query[2])){
    $action = $page_query[1];
    $calender->$action($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and (!is_valid_action($page_query[1]) or !isset($page_query[2]))){
    $_SESSION['Notify'] = '404 Please enter a valid route';
}else{
    $calender->list();
}