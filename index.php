<!DOCTYPE html>
<html>
<head>
<title>Movie List</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
table, th, td {
	border: 1px solid black;
	border-collapse: collapse;
	text-align: center;
}
</style>
<?php 
//displays movie data in table by grabbing them from file and wrapping data in proper html
function DisplayMovies($fileName,$filterCode){
	//open file of movies
	$movieFile=fopen($fileName,"r") or die("Unable to open file");
	
	//grab each line from file and construct string to display data in table
	while(!feof($movieFile)){
		$fline=fgets($movieFile);
		$name=strtok($fline,"_");
		$interestCode=strtok("_");
		$interestStr=DecodeInterest($interestCode);
		$buy=strtok(" ");
		if($name=='')
			break;

		//output the information into the table
		if(($interestCode==$filterCode)||($filterCode==0)){//filter code is 0 if no filter is applied; otherwise, print only movies that satisfy filter
			//prints all movie info
			echo "<tr><td>".$name."</td><td>".$interestStr."</td><td>".$buy."</td>";
			//creates the edit button
			echo "<td><form method='get'>";
			echo "<input type='hidden' name='movie' value='".$name."'>";
			echo "<input type='submit' name='button' value='Edit' style='width:100%'>";
			echo "</form></td>";
			//creates the remove button
			echo "<td><form method='get'>";
			echo "<input type='hidden' name='movie' value='".$name."'>";
			echo "<input type='submit' name='button' value='Remove' style='width:100%'>";
			echo "</form></td></tr>";
		}
	}
	fclose($movieFile); //close the file
}

//takes interest number from file and decodes it into corresponding string of names
function DecodeInterest(int $interestNum){
	$interestStr='';
	if($interestNum>=32){
		$interestStr.="Dad, ";
		$interestNum-=32;
	}
	if($interestNum>=16){
		$interestStr.="Mom, ";
		$interestNum-=16;
	}
	if($interestNum>=8){
		$interestStr.="Sam, ";
		$interestNum-=8;
	}
	if($interestNum>=4){
		$interestStr.="Jacob, ";
		$interestNum-=4;
	}
	if($interestNum>=2){
		$interestStr.="Katie, ";
		$interestNum-=2;
	}
	if($interestNum>=1){
		$interestStr.="Billy, ";
		$interestNum-=1;
	}
	if(strlen($interestStr)>0)
		$interestStr=substr($interestStr,0,-2);
	return $interestStr;
}

//removes movie and redirects to add movie page so user can "edit" the movie
function EditMovie($fileName,$movieName){
	RemoveMovie($fileName,$movieName);//first remove the movie from the file
	$path="addMovie.php?movieName=".$movieName;
	header("Location: $path");
}

//removes specified movie from file
function RemoveMovie($fileName,$movieName){
	$movieFile=fopen($fileName,"a+") or die("Unable to open file"); //open movie file
	$tmpFile=fopen("data/temp.txt","w") or die("Unable to open file"); //create temp file

	while(!feof($movieFile)){//copy all lines of movie file except deleted line
		$fline=fgets($movieFile);
		$name=strtok($fline,"_");
		if($name!=$movieName) //don't copy line we're removing
			fwrite($tmpFile,$fline);
	}
	fclose($movieFile);//close files before deleting/renaming
	fclose($tmpFile);
	unlink($fileName); //delete movie file so we can replace it with corrected temp file
	rename("data/temp.txt",$fileName);
}


//PHP EXECUTED BEFORE FUNCTIONS ARE CALLED
//if the user wants to filter the list, generate a filter code to use when displaying the list
$filterCode=0;
if(!empty($_GET['filterSubmit'])){ //if the filter form was submitted, figure out which buttons were pressed
	if(!empty($_GET['billyBttn']))
		$filterCode+=1;
	if(!empty($_GET['katieBttn']))
		$filterCode+=2;
	if(!empty($_GET['jacobBttn']))
		$filterCode+=4;
	if(!empty($_GET['samBttn']))
		$filterCode+=8;
	if(!empty($_GET['momBttn']))
		$filterCode+=16;
	if(!empty($_GET['dadBttn']))
		$filterCode+=32;
}

//if the movie object isnt empty, the user wants to modify the list
if((!empty($_GET['movie']))&&(!empty($_GET['button']))){
	//remove the movie if user presses remove button
	if($_GET['button']=="Remove"){
		$movieToRemove=$_GET['movie'];
		RemoveMovie("data/movies.txt",$movieToRemove);
	//edit movie entry if the user presses edit button
	}else if($_GET['button']=="Edit"){
		$editMovie=$_GET['movie'];
		EditMovie("data/movies.txt",$editMovie);
	}
}
?>
</head>
<body>
<center><h1>List of Movies to Watch</h1>
<p>Missing a movie? Add another to the list <a href='addMovie.php'>here</a></p>
<h3>Filter by who is watching</h3>

<!-- this form allows user to filter list by who is interested in the movie -->
<form method='get'>
	<input type="checkbox" id="billyBttn" name='billyBttn'>
	<label for="billyBttn">Billy</label>
	<input type="checkbox" id="katieBttn" name='katieBttn'>
	<label for="katieBttn">Katie</label>
	<input type="checkbox" id="jacobBttn" name='jacobBttn'>
	<label for="jacobBttn">Jacob</label>
	<input type="checkbox" id="samBttn" name='samBttn'>
	<label for="samBttn">Sam</label>
	<input type="checkbox" id="momBttn" name='momBttn'>
	<label for="momBttn">Mom</label>
	<input type="checkbox" id="dadBttn" name='dadBttn'>
	<label for="dadBttn">Dad</label>
	<input type='submit' name='filterSubmit' value='Filter'><br><br>
</form>

<!-- table of movies; printed with php function -->
<table style='width:100%; max-width: 650px;'>
	<tr>
		<th>Movie Name</th>
		<th>Who Wants to Watch</th>
		<th>Do we have to buy it?</th>
		<th>Edit</th>
		<th>Remove</th>
		<?php DisplayMovies("data/movies.txt",$filterCode); 
		?>
	</tr>	
</table></center>
</body>
</html>