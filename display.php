<html>
<head>
    <title> Simple Online Social List </title>
    <style type = "text/css">
    td, th, table {border: thin solid black;}
    </style>
</head>
<body>

<?php
    
// Get input data
    $name= $_POST["name"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $facebook = $_POST["facebook"];
    $twitter = $_POST["twitter"];
    $action = $_POST["action"];
	
	if ($name == '' && $action != display) {
		print "Error - empty name";
		exit;
	}

// Connect to MySQL
$db = mysql_connect("db1.cs.uakron.edu:3306", "smg134", "aeCh1xee");
if (!$db) {
     print "Error - Could not connect to MySQL";
     exit;
}

// Select the database
$er = mysql_select_db("ISP_smg134");
if (!$er) {
    print "Error - Could not select the database";
    exit;
}

if($action == "display")
    $query = "";
else if ($action == "insert")
    $query = "insert into Contacts values('$name', '$phone', '$address', '$facebook', '$twitter')";
else if ($action == "update")
    $query = "update Contacts set Phone = '$phone', Address = '$address', Facebook = '$facebook', Twitter = '$twitter' where Name = '$name'";
else if ($action == "delete")
    $query = "delete from Contacts where Name = '$name'";

if($query != ""){
    trim($query);
    
    $result = mysql_query($query);
    if (!$result) {
        print "Error - the query could not be executed";
        $error = mysql_error();
        print "<p>" . $error . "</p>";
    }
}
    
// Final Display of All Entries
$query = "SELECT * FROM Contacts";
$result = mysql_query($query);
if (!$result) {
    print "Error - the query could not be executed";
    $error = mysql_error();
    print "<p>" . $error . "</p>";
    exit;
}

// Get the number of rows in the result, as well as the first row
//  and the number of fields in the rows
$num_rows = mysql_num_rows($result);

print "<table><caption> <h2> Contacts ($num_rows) </h2> </caption>";
print "<tr align = 'center'>";

$row = mysql_fetch_array($result);
$num_fields = mysql_num_fields($result);

// Produce the column labels
$keys = array_keys($row);
for ($index = 0; $index < $num_fields; $index++) 
    print "<th>" . $keys[2 * $index + 1] . "</th>";
print "</tr>";
    
// Output the values of the fields in the rows
for ($row_num = 0; $row_num < $num_rows; $row_num++) {
    print "<tr align = 'center'>";
    $values = array_values($row);
    for ($index = 0; $index < $num_fields; $index++){
        $value = htmlspecialchars($values[2 * $index + 1]);
        print "<th>" . $value . "</th> ";
    }
    print "</tr>";
    $row = mysql_fetch_array($result);
}
print "</table>";
?>
</body>
</html>