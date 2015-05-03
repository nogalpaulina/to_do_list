<!DOCTYPE html>
<html>
<head>
<title>To do list</title>
</head>
<body>
<form method="post">
<?php

	//*  Connect to MySQL and Database

	$db = mysqli_connect('localhost','root','', 'todo');

	if (!$db)
	{
		print "<h1>Unable to Connect to MySQL</h1>";
	}

	//* INSERT new items, if there is something to add.

	if (isset($_POST['addbutton']))
	{
		$myaddbutton = trim($_POST['addbutton']);
	} else {
		$myaddbutton = '';
	}

	if ($myaddbutton == 'Add Item')
	{

		if (isset($_POST['activity']))
		{
			$activity = trim($_POST['activity']);
		} else {
			$activity = '';
		}

		if (isset($_POST['deadline']))
		{
			$deadline = trim($_POST['deadline']);
		} else {
			$deadline = '';
		}

		if (isset($_POST['completed']))
		{
			$completed= trim($_POST['completed']);
		} else {
			$completed= '';
		}
		if (empty($activity) || empty($deadline))
		{
			print "<p style='color: red'>Must Fill Out All Fields Correctly</p>";
		} else {
			$statement = "insert into item (activity, deadline, completed) ";
			$statement .= "values (";
			$statement .= "'".$activity."', '".$deadline."', '".$completed."'";
			$statement .= ")";

			$result = mysqli_query($db, $statement);
		}

	}

	//*  SELECT from table and display results

	$statement  = "SELECT * ";
	$statement .= "FROM item ";

	$result = mysqli_query($db, $statement);

	$outputDisplay = "";

	if (!$result) {
		$outputDisplay .= "<p style='color: red;'>MySQL No: ".mysqli_errno($db)."<br>";
		$outputDisplay .= "MySQL Error: ".mysqli_error($db)."<br>";
		$outputDisplay .= "<br>SQL: ".$sql_statement."<br>";
		$outputDisplay .= "<br>MySQL Affected Rows: ".mysqli_affected_rows($db)."</p>";

	} else {

		$outputDisplay  = "<h3>To do items:</h3>";

		$outputDisplay .= '<table border=1 style="color: black;">';
		$outputDisplay .= '<tr><th>Activity</th><th>Deadline</th><th>Completed</th><th>Delete?</th></tr>';

		$numresults = mysqli_num_rows($result);
		$numDisplayed = 0;

		for ($i = 0; $i < $numresults; $i++)
		{

			$row = mysqli_fetch_array($result);

			$id = $row['id'];
			$activity = $row['activity'];
			$deadline = $row['deadline'];
			$completed = $row['completed'];

			 if (isset($_POST['delete'.$id]))
		        {
		        	$delete = $_POST['delete'.$id];
		        } else {
		        	$delete = 'N';
		        }

			if ($delete == 'Y')
			{
				$statement = "DELETE FROM item WHERE id = $id";
				mysqli_query($db, $statement);
			} else {
				if (isset($_POST['completed'.$id]))
		        	{
		        		$completed = 1;
		        		$statement = "UPDATE item SET completed = $completed WHERE id = $id";
					mysqli_query($db, $statement);
		        	}

				if ($numDisplayed % 2 == 0)
				{
					 $outputDisplay .= "<tr style=\"background-color: lightgrey;\">";
				} else {
					 $outputDisplay .= "<tr style=\"background-color: white;\">";
				}
				$strike = ($completed == 1) ? "<strike>" : "";
				$outputDisplay .= "<td>".$strike.$activity."</td>";
				$outputDisplay .= "<td>".$deadline."</td>";
				$done_or_checkbox = ($completed == 1) ? "DONE" : "<input type='checkbox' name='completed".$id."' value='1'>";
				$outputDisplay .= "<td>$done_or_checkbox</td>";
				$outputDisplay .= "<td><input type='checkbox' name='delete".$id."' value='Y'></td>";

				$outputDisplay .= "</tr>";

				$numDisplayed++;
			}

		}

		$outputDisplay .= "</table>";

	}

	$outputDisplay .= "<br /><br /><b>Number of Rows in Results: $numDisplayed </b><br /><br />";
	print $outputDisplay;
?>
	<input type="submit" value="Delete/Update items">
	</form>

	<h3>Add an item to your list:</h3>
	<form method="post">
	<input id="activity" name="activity" type="text" placeholder="Activity name...">
	<input id="deadline" name="deadline" type="text" placeholder="2015-05-03">
	Completed? <input type='checkbox' id="completed" name='completed' value='1'>
	<input type="submit" name="addbutton" value="Add Item" />
	</form>

</body>
</html>