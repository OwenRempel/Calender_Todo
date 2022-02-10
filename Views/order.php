<?php


if(isset($page_query[1]) and !empty($page_query[1])){
   echo "<a href='/order/' class='btn blue darken-3'>Back</a>";
}else{
    echo "<a href='/' class='btn blue darken-3'>Back</a>";
}

class Order{
    public static function create(){
        $DB = new Database;
       
        if(isset($_POST['Date_add'])){
        
            $DB->query('INSERT INTO Orders (Customer, Calender, Number, Date) VALUES (:customer, :calender, :number, :date)', array(
                'customer'=>$_POST['Customer'],
                'calender'=>$_POST['Calender'],
                'number'=>$_POST['Number'],
                'date'=>$_POST['Date']
            ));
            $_SESSION['Notify'] = 'Order Added';
            $_SESSION['Notify_error'] = 0;
            
            header('location:'.$_SERVER['REDIRECT_URL']);
        }
        $cals = $DB->query('SELECT Name, ID from Calenders');
        $cust = $DB->query('SELECT Name, ID from Customers order by Name ASC');
        ?>
        <h3>Add Order</h3>
            <form action="" method="post">
                <div class="input-field col s6">
                    <select name="Customer">
                        <option value=""></option>
                        <?php
                            foreach($cust as $customer){
                                echo "<option value='".$customer['ID']."'>".$customer['Name']."</option>";
                            }
                        ?>
                    </select>
                    <label for="Customer" class='active'>Customer</label>
                </div>
                <div class="input-field col s6">
                    <select name="Calender">
                        <option value=""></option>
                        <?php
                            foreach($cals as $cal){
                                echo "<option value='".$cal['ID']."'>".$cal['Name']."</option>";
                            }
                        ?>
                    </select>
                    <label for="Customer" class='active'>Calender</label>
                </div>
                <div class="input-field col s6">
                    <input type="number" name="Number" >
                    <label for="Number">Number</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" class="datepicker" name="Date" value='<?php echo date("Y-m-d");?>'>
                    <label for="Date">Date</label>
                </div>
                <button class="btn blue darken-3" type="submit" name="Date_add" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function update($ID){
        if(isset($_GET['back'])){
            if($_GET['back'] > 5){
                $num = $_GET['back'] - 5;
                $back = '/order#n'.$num;
            }else{
                $back = '/order';
            }
            
        }else{
            $back = '/order';
        }
        $DB = new Database;
        
        if(isset($_POST['Date_up'])){
           
            $send_check = $DB->query('UPDATE Orders SET Customer=:customer, Calender=:calender, Number=:number, Date=:date WHERE ID=:id',array(
                'customer'=>$_POST['Customer'],
                'calender'=>$_POST['Calender'],
                'number'=>$_POST['Number'],
                'date'=>$_POST['Date'],
                'id'=>$_POST['ID']
            ));
            $_SESSION['Notify'] = 'Order Updated';
            $_SESSION['Notify_error'] = 0;
           
            header('location:'.$back);
        }
        
        $data = $DB->query('SELECT Customer, Calender, Number, Date, ID FROM Orders WHERE ID=:id', array('id'=>$ID));
        if(!isset($data[0])){
            $_SESSION['Notify'] = '404 Please Enter a valid ID';
            $_SESSION['Notify_error'] = 1;
            return;
        }

        $cals = $DB->query('SELECT Name, ID from Calenders');
        $cust = $DB->query('SELECT Name, ID from Customers Order By Name ASC');

        ?>
        <h3>Update Order</h3>
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
                    <select name="Customer">
                        <option value=""></option>
                        <?php
                            foreach($cust as $customer){
                                if($data[0]['Customer'] == $customer['ID']){
                                    echo "<option value='".$customer['ID']."' selected>".$customer['Name']."</option>";

                                }else{
                                    echo "<option value='".$customer['ID']."'>".$customer['Name']."</option>";
                                }
                            }
                        ?>
                    </select>
                    <label for="Customer" class='active'>Customer</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Number" value="<?php echo $data[0]['Number'];?>">
                    <label for="Number">Number</label>
                </div>
                <div class="input-field col s6">
                    <input type="text" name="Date" class="datepicker" value="<?php echo $data[0]['Date'];?>">
                    <label for="Amount">Amount</label>
                </div>
                <input type="hidden" name="ID" value="<?php echo $ID;?>">
                <button class="btn blue darken-3" type="submit" name="Date_up" value='1'>Submit
                </button>
            </form>
        <?php
    }
    public static function delete($ID){
        $DB = new Database;
        if(isset($_POST['Date_del'])){
            $DB->query('DELETE FROM Orders WHERE ID=:id',array('id'=>$_POST['ID']));
            $_SESSION['Notify'] = 'Order Deleted';
            $_SESSION['Notify_error'] = 0;
            header('location:/order');
        }
       ?>
            <form action="" method="post">
                <h5 class="deleteBack">Are you sure that you want to delete this Order?</h5>
                <input type="hidden" name="ID" value='<?php echo $ID;?>'>
                <button class="btn red darken-3" type="submit" name="Date_del" value='1'>Submit
            </form>
       <?php
    }
    public static function list(){
        $DB = new Database;
        $list_data = $DB->query(
            'SELECT Customers.Name as CustomerName, Calenders.Name as CalName, Orders.Number, Orders.Delivered, Orders.Paid, Orders.Date, Orders.Adate, Orders.ID 
            from ((Orders INNER JOIN Calenders ON Orders.Calender = Calenders.ID) INNER JOIN Customers ON Orders.Customer = Customers.ID) order by Customers.Name ASC'
        );
        ?>
            <a href='/order/create' class="btn blue darken-3 menu-wide">Add</a>
            <div class="list">
                <?php
                    $i = 0;
                    foreach($list_data as $row){  
                        $i += 1;                    
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
                            <span class="name"><?php echo $row['CustomerName'];?></span>
                            <span class="number">Number: <?php echo $row['Number'];?> </span>
                            <span class="adate"> <?php echo date('M d', strtotime($row['Adate']));?></span>
                            
                            
                            <span class="moreinfo hide">
                                <span>
                                Type:<?php echo $row['CalName'];?>
                                </span>
                                <span>
                                Delivered:<?php echo $row['Delivered'];?>
                                </span>
                                <span>
                                Paid:<?php echo $row['Paid'];?>
                                </span>
                                <span>
                                <?php echo $row['Date'];?>
                                </span>
                                
                                
                            </span>
                            <span class="list-item-actions hide">
                            <a href='/order/update/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Edit</a>
                            <a href='/action/deliver/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Delivered</a>
                            <a href='/action/paid/<?php echo $row['ID'];?>?back=<?php echo $i;?>' class="btn blue darken-3">Paid</a>
                            <a href='/order/delete/<?php echo $row['ID'];?>' class="btn red darken-3">Delete</a>
                            </span>
                        </div>
                    <?php
                    }
                ?>
            </div>
            
           
            
        <?php
       
    }
}
$Order = new Order;
if(isset($page_query[1]) and $page_query[1] == 'create'){
    $Order->create();

}elseif(isset($page_query[1]) and is_valid_action($page_query[1]) and isset($page_query[2])){
    $action = $page_query[1];
    $Order->$action($page_query[2]);
}elseif(isset($page_query[1]) and !empty($page_query[1]) and (!is_valid_action($page_query[1]) or !isset($page_query[2]))){
    $_SESSION['Notify'] = '404 Please enter a valid route';
    $_SESSION['Notify_error'] = 1;
}else{
    $Order->list();
}
