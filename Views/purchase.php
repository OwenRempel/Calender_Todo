<?php


if(isset($page_query[1]) and !empty($page_query[1])){
   echo "<a href='/purchase/' class='btn blue darken-3'>Back</a>";
}else{
    echo "<a href='/' class='btn blue darken-3'>Back</a>";
}

class Purchase{
    public static function create(){
        $DB = new Database;
        if(isset($_POST['Purc_add'])){
        
            $DB->query('INSERT INTO Purchases (Calender, Number, Amount) VALUES (:calender, :number, :amount)', array(
                'calender'=>$_POST['Calender'],
                'number'=>$_POST['Number'],
                'amount'=>$_POST['Amount']
            ));
            $_SESSION['Notify'] = 'Purchase Added';
            $_SESSION['Notify_error'] = 0;
            
            header('location:'.$_SERVER['REDIRECT_URL']);
        }
        $cals = $DB->query('SELECT Name, ID from Calenders');
        ?>
        <h3>Add Purchase</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <select name="Calender">
                        <option value=""></option>
                        <?php
                            foreach($cals as $cal){
                                echo "<option value='".$cal['ID']."'>".$cal['Name']."</option>";
                            }
                        ?>
                    </select>
                    <label for="Calender" class='active'>Calender</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Number" >
                    <label for="Number">Number</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Amount" >
                    <label for="Amount">Amount</label>
                </div>
                <button class="btn blue darken-3" type="submit" name="Purc_add" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function update($ID){
        $DB = new Database;
        
        if(isset($_POST['Purc_up'])){
            $userCheck = $DB->query("SELECT Calender, ID FROM Purchases WHERE Calender = :calender", array('calender'=>$_POST['Calender']));
            if(!isset($userCheck[0]) or $userCheck[0]['ID'] == $_POST['ID']){
                $send_check = $DB->query('UPDATE Purchases SET Calender=:calender, Number=:number, Amount=:amount WHERE ID=:id',array(
                    'calender'=>$_POST['Calender'],
                    'number'=>$_POST['Number'],
                    'amount'=>$_POST['Amount'],
                    'id'=>$_POST['ID']
                ));
                $_SESSION['Notify'] = 'Purchase Updated';
                $_SESSION['Notify_error'] = 0;
            }else{
                $_SESSION['Notify'] = 'That Purchase already exists';
                $_SESSION['Notify_error'] = 1;
            }
            header('location:/purchase');
        }
        
        $data = $DB->query('SELECT Calender, Number, Amount FROM Purchases WHERE ID=:id', array('id'=>$ID));
        if(!isset($data[0])){
            $_SESSION['Notify'] = '404 Please Enter a valid ID';
            $_SESSION['Notify_error'] = 1;
            header('location:/purchase');
        }

        $cals = $DB->query('SELECT Name, ID from Calenders');

        ?>
        <h3>Update Purchase</h3>
            <form action="" method="post">
            <div class="input-field col s6">
                    <select name="Calender">
                        <option value=""></option>
                        <?php
                            foreach($cals as $cal){
                                if($data[0]['Calender'] == $cal['ID']){
                                    echo "<option value='".$cal['ID']."' selected>".$cal['Name']."</option>";

                                }else{
                                    echo "<option value='".$cal['ID']."'>".$cal['Name']."</option>";
                                }
                            }
                        ?>
                    </select>
                    <label for="Calender" class='active'>Calender</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Number" value="<?php echo $data[0]['Number'];?>">
                    <label for="Number">Number</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Amount" value="<?php echo $data[0]['Amount'];?>">
                    <label for="Amount">Amount</label>
                </div>
                <input type="hidden" name="ID" value="<?php echo $ID;?>">
                <button class="btn blue darken-3" type="submit" name="Purc_up" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function delete($ID){
        $DB = new Database;
        if(isset($_POST['Purc_del'])){
            $DB->query('DELETE FROM Purchases WHERE ID=:id',array('id'=>$_POST['ID']));
            $_SESSION['Notify'] = 'Purchase Deleted';
            $_SESSION['Notify_error'] = 0;
            header('location:/purchase');
        }
       ?>
            <form action="" method="post">
                <h5 class="deleteBack">Are you sure that you want to delete this Purchase?</h5>
                <input type="hidden" name="ID" value='<?php echo $ID;?>'>
                <button class="btn red darken-3" type="submit" name="Purc_del" value='1'>Submit
            </form>
       <?php
    }
    public static function list(){
        $DB = new Database;
        $list_data = $DB->query('SELECT Calenders.Name, Purchases.Number, Purchases.Amount, Purchases.Adate, Purchases.ID from Purchases INNER JOIN Calenders ON Purchases.Calender = Calenders.ID');
        ?>
        <div class="list">
                <?php
                    foreach($list_data as $row){
                                    
                    ?>
                        <div class="list-item small">
                            <span class="name"><?php echo $row['Name'];?></span>
                            <span class="adate"> <?php echo date('M d', strtotime($row['Adate']));?></span>
                            
                            
                            <span class="moreinfo hide">
                                <span>
                                    Number:<?php echo $row['Number'];?>
                                </span>
                                <span>
                                    Amount:$<?php echo $row['Amount'];?>
                                </span>
                                
                            </span>
                            <span class="list-item-actions hide">
                            <a href='/purchase/update/<?php echo $row['ID'];?>' class="btn blue darken-3">Edit</a>
                            <a href='/purchase/delete/<?php echo $row['ID'];?>' class="btn red darken-3">Delete</a>
                            </span>
                        </div>
                    <?php
                    }
                ?>
            </div>
            <br>
            <a href='/purchase/create' class="btn blue darken-3">Add</a>
            
        <?php
       
    }
}
$purchase = new Purchase;
if(isset($page_query[1]) and $page_query[1] == 'create'){
    $purchase->create();

}elseif(isset($page_query[1]) and is_valid_action($page_query[1]) and isset($page_query[2])){
    $action = $page_query[1];
    $purchase->$action($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and (!is_valid_action($page_query[1]) or !isset($page_query[2]))){
    $_SESSION['Notify'] = '404 Please enter a valid route';
    $_SESSION['Notify_error'] = 1;
}else{
    $purchase->list();
}