<html>
<head>
    <title> Database Programming with PHP </title>
    <style type = "text/css">
    td, th, table {border: thin solid black;}
    </style>
</head>
<body>

<?php
    
// Get input data
    $id = $_POST["id"];
    $type = $_POST["type"];
    $miles = $_POST["miles"];
    $year = $_POST["year"];
    $state = $_POST["state"];
    $action = $_POST["action"];
    $statement = $_POST["statement"];
    
    // If any of numerical values are blank, set them to zero
    if ($id == "") $id = 0;
    if ($miles == "") $miles = 0.0;
    if ($year == "") $year = 0;
    if ($state == "") $state = 0;

// Connect to MSSQL
$serverName = "extracreditserver.database.windows.net";
$connectionOptions = array(
    "Database" => "ISP Extra Credit",
    "UID" => "smg134",
    "PWD" => "D0gmeatG00dyear"
);
$conn = sqlsrv_connect($serverName, $connectionOptions);

// print "<b> The action is: </b> $action <br />";

if($action == "display")
    $query = "";
else if ($action == "insert")
    $query = "insert into Corvettes values($id, '$type', $miles, $year, $state)";
else if ($action == "update")
    $query = "update Corvettes set Body_style = '$type', Miles = $miles, Year = $year, State = $state where Vette_id = $id";
else if ($action == "delete")
    $query = "delete from Corvettes where Vette_id = $id";
else if ($action == "user")
    $query = $statement;


if($query != ""){
    trim($query);
    $query_html = htmlspecialchars($query);
    print "<b> The query is: </b> " . $query_html . "<br />";
    
    $result = sqlsrv_query($conn, $query);
}
    
// Final Display of All Entries
$query = "SELECT * FROM Corvettes";
$result = sqlsrv_query($conn, $query, array(), array( "Scrollable" => 'static' )); 

// Get the number of rows in the result, as well as the first row
//  and the number of fields in the rows
$num_rows = sqlsrv_num_rows($result);
if ($num_rows === false)
   print "Error in retrieveing row count.";

print "<table><caption> <h2> Cars ($num_rows) </h2> </caption>";
print "<tr align = 'center'>";

$row = sqlsrv_fetch_array($result);
$num_fields = sqlsrv_num_fields($result);

print "<b> Rows: </b> " . $num_rows . "<br />";
print "<b> Fields: </b> " . $num_fields . "<br />";

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
    $row = sqlsrv_fetch_array($result);
}
print "</table>";
?>
</body>
</html>
