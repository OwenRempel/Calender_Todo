<?php


if(isset($page_query[1]) and !empty($page_query[1])){
   echo "<a href='/customer/' class='btn blue darken-3'>Back</a>";
}else{
    echo "<a href='/' class='btn blue darken-3'>Back</a>";
}

class Customer{
    public static function create(){
        $DB = new Database;
        if(isset($_POST['Cust_add'])){
            $userCheck = $DB->query("SELECT Name FROM Customers WHERE Name = :name", array('name'=>$_POST['Name']));
        
            if(!isset($userCheck[0])){
                 $DB->query('INSERT INTO Customers (Name, Phone, Location) VALUES (:name, :phone, :location)', array(
                    'name'=>$_POST['Name'],
                    'phone'=>$_POST['Phone'],
                    'location'=>$_POST['Location']
                ));
                $_SESSION['Notify'] = 'Customer Added';
                $_SESSION['Notify_error'] = 0;
            }else{
                $_SESSION['Notify'] = 'That User already exists';
                $_SESSION['Notify_error'] = 1;
            }
            header('location:'.$_SERVER['REDIRECT_URL']);
        }

        ?>
        <h3>Add Customer</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <input type="text" name="Name" >
                    <label for="Name">Name</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Phone" >
                    <label for="Phone">Phone</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Location" >
                    <label for="Location">Location</label>
                </div>
                <button class="btn blue darken-3" type="submit" name="Cust_add" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function update($ID){

        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/customer#n'.$num;
            }else{
                $back = '/customer';
            }
            
        }else{
            $back = '/customer';
        }

        $DB = new Database;
        
        if(isset($_POST['Cust_up'])){
            $userCheck = $DB->query("SELECT Name, ID FROM Customers WHERE Name = :name", array('name'=>$_POST['Name']));
            if(!isset($userCheck[0]) or $userCheck[0]['ID'] == $_POST['ID']){
                $send_check = $DB->query('UPDATE Customers SET Name=:name, Phone=:phone, Location=:location WHERE ID=:id',array(
                    'name'=>$_POST['Name'],
                    'phone'=>$_POST['Phone'],
                    'location'=>$_POST['Location'],
                    'id'=>$_POST['ID']
                ));
                $_SESSION['Notify'] = 'Customer Updated';
                $_SESSION['Notify_error'] = 0;
            }else{
                $_SESSION['Notify'] = 'That User already exists';
                $_SESSION['Notify_error'] = 1;
            }
            header('location:'.$back);
        }
        
        $data = $DB->query('SELECT Name, Phone, Location FROM Customers WHERE ID=:id', array('id'=>$ID));
        if(!isset($data[0])){
            $_SESSION['Notify'] = '404 Please Enter a valid ID';
            $_SESSION['Notify_error'] = 1;
            header('location:/customer');
        }

        ?>
        <h3>Update Customer</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <input type="text" name="Name" value='<?php echo $data[0]['Name'];?>'>
                    <label for="Name">Name</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Phone" value="<?php echo $data[0]['Phone'];?>">
                    <label for="Phone">Phone</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Location" value="<?php echo $data[0]['Location'];?>">
                    <label for="Location">Location</label>
                </div>
                <input type="hidden" name="ID" value="<?php echo $ID;?>">
                <button class="btn blue darken-3" type="submit" name="Cust_up" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function delete($ID){
        $DB = new Database;
        if(isset($_POST['Cust_del'])){
            $DB->query('DELETE FROM Customers WHERE ID=:id',array('id'=>$_POST['ID']));
            $_SESSION['Notify'] = 'Customer Deleted';
            $_SESSION['Notify_error'] = 0;
            header('location:/customer');
        }
       ?>
            <form action="" method="post">
                <h5 class="deleteBack">Are you sure that you want to delete this Customer?</h5>
                <input type="hidden" name="ID" value='<?php echo $ID;?>'>
                <button class="btn red darken-3" type="submit" name="Cust_del" value='1'>Submit
            </form>
       <?php
    }
    public static function list(){
        global $CalenderPrices;
        $DB = new Database;
        $list_data = $DB->query('SELECT Name, Phone, Gallery, Location, Adate, ID,
         (SELECT SUM(Number) FROM Orders WHERE Customer = Customers.ID) as Number,
         (SELECT SUM(Delivered) FROM Orders WHERE Customer = Customers.ID) as Delivered,
         (SELECT SUM(Paid) FROM Orders WHERE Customer = Customers.ID) as Paid from Customers Order BY Name ASC');
       
        ?>
        <a href='/customer/create' class="btn blue darken-3">Add</a>
        <div class="list">
                <?php
                $i = 0;
                    foreach($list_data as $row){
                        $i += 1;
                        $types = $DB->query('SELECT Calenders.Name, Number from Orders inner join Calenders on Orders.Calender = Calenders.ID WHERE Customer=:cust', array('cust'=>$row['ID']));
                        $ty = [];
                        foreach($types as $type){
                            $ty[$type['Name']]['count'] += $type['Number'];
                        }   
                        if($row['Delivered'] == $row['Number'] and $row['Paid'] != $row['Number']){
                            $deliveredCheck = 'delivered';
                        }elseif($row['Delivered'] == $row['Number'] and $row['Paid'] == $row['Number']){
                            $deliveredCheck = 'paid';
                        }elseif($row['Delivered'] != $row['Number'] and $row['Paid'] == $row['Number']){
                            $deliveredCheck = 'money';
                        }else{
                            $deliveredCheck = '';
                        }
                    ?>
                        <div id='n<?php echo $i;?>' class="list-item <?php echo $deliveredCheck; ?> small">
                            <span class="name"><?php echo $row['Name'];?></span>
                            <span>Price:$<?php 
                            if($row['gallery'] == 0 and $row['Number'] == 1){
                                echo $row['Number'] * $CalenderPrices['mainPrice'];
                            }elseif($row['gallery'] == 0 and $row['Number'] > 1){
                                echo $row['Number'] * $CalenderPrices['discount'];
                            }else{
                                echo $row['Number'] * $CalenderPrices['discount'];
                            }
                            ?></span>
                            <span class="adate"> <?php echo date('M d', strtotime($row['Adate']));?></span>
                            
                            
                            <span class="moreinfo hide">
                                <span>
                                <?php 
                                    foreach($ty as $k=>$it){
                                        echo implode(':', [$k, $it['count']]).'  ';
                                    }
                                ?>
                                </span>
                                <!-- <span>
                                Phone:<?php echo $row['Phone'];?>
                                </span>
                                <span>
                                Location:<?php echo $row['Location'];?>
                                </span> -->
                                
                                
                            </span>
                            <span class="list-item-actions hide">
                            <a href='/customer/update/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Edit</a>
                            <a href='/action/deliverAll/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Delivered</a>
                            <a href='/action/paidAll/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Paid</a>
                            <a href='/customer/delete/<?php echo $row['ID'];?>' class="btn red darken-3">Delete</a>
                            </span>
                        </div>
                    <?php
                    }
                ?>
            </div>
            
            
        <?php
       
    }
}
$customer = new Customer;
if(isset($page_query[1]) and $page_query[1] == 'create'){
    $customer->create();

}elseif(isset($page_query[1]) and is_valid_action($page_query[1]) and isset($page_query[2])){
    $action = $page_query[1];
    $customer->$action($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and (!is_valid_action($page_query[1]) or !isset($page_query[2]))){
    $_SESSION['Notify'] =  '404 Please enter a valid route';
    $_SESSION['Notify_error'] = 1;
}else{
    $customer->list();
}