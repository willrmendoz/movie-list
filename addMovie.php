<?php declare(strict_types=1); ?>
<!DOCTYPE html>
<html>
<head>
<title>Add Movie</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
.error {color: #FF0000;}
</style>
</head>

<?php
	//global variables
	$movieNameErrorMsg=$buyMovieErrorMsg=$interestErrorMsg='';//error message declarations; displays messages if error detected
	$movieName=$buyMovie=''; //values that will be written to the file
	$interestCode=0;//number to keep track of who is interested in movie; make filesizes smaller
	
	function ValidateForm(){ //ensures the form was filled out correctly before adding the movie to the list
		//grab global variables
		global $movieName, $buyMovie, $interestCode;
		global $movieNameErrorMsg, $buyMovieErrorMsg, $interestErrorMsg;
		$errorTripped=false;//tracks if an error is detected throughout function
		
		//check the movie name isnt empty
		if(empty($_GET['movieName'])){
			$movieNameErrorMsg = "Movie name cannot be blank";
			$errorTripped=true;
		}
		else $movieName=htmlspecialchars($_GET['movieName']);
		
		//check that at least one name is checked and encode button states into one number
		if(!empty($_GET['billyBttn']))
			$interestCode+=1;
		if(!empty($_GET['katieBttn']))
			$interestCode+=2;
		if(!empty($_GET['jacobBttn']))
			$interestCode+=4;
		if(!empty($_GET['samBttn']))
			$interestCode+=8;
		if(!empty($_GET['momBttn']))
			$interestCode+=16;
		if(!empty($_GET['dadBttn']))
			$interestCode+=32;
		if($interestCode==0){//meaning nobody was selected
			$interestErrorMsg="Select at least one person";
			$errorTripped=true;
		}
		
		//check if user chose an option on purchasing movie
		if(empty($_GET['buyMovie'])){
			$buyMovieErrorMsg = "Please select yes or no<br>";
			$errorTripped=true;
		}
		else $buyMovie=htmlspecialchars($_GET['buyMovie']);
		
		//Form filled out successfully, add to list and redirect
		if(!$errorTripped){
			$movieFile=fopen("data/movies.txt","a+") or die("Unable to open file");
			fwrite($movieFile,$movieName."_".$interestCode."_".$buyMovie."\n");
			fclose($movieFile);
			//call batch file that sorts list alphabetically after adding a movie
			system("sortFile.bat");
			header("Location: index.php");
		}
	}
	
	//call the function
	ValidateForm();
?>
<body>
<form method='get' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
	<input type="text" id="movieName" name='movieName' placeholder="Name of Movie" value='<?php echo $movieName ?>'>
	<span class='error'><?php echo $movieNameErrorMsg; ?></span><br>

	<p>Who is interested in this movie? <span class='error'><?php echo $interestErrorMsg; ?></span></p>
	<input type="checkbox" id="billyBttn" name='billyBttn' value=1>
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
	<label for="dadBttn">Dad</label><br>

	<p>Do we need to buy this movie?</p>
	<span class='error'><?php echo $buyMovieErrorMsg; ?></span>
	<input type="radio" id="yesBuyMovie" name="buyMovie" value='Yes'>
	<label for="yesBuyMovie">Yes</label><br>
	<input type="radio" id="noBuyMovie" name="buyMovie" value='No'>
	<label for="noBuyMovie">No</label><br>

	<input type="submit" value="Submit">
</form>

<a href='index.php'>Back to List</a>
</body>
</html>