<?php
include 'util.php';
my_session_start();

// check that the user is logged in - if not, redirect to login.
if (!isset($_SESSION["user_email_address"])) {
    header('Location: login.php');
    exit;
}

require 'connect.php';

$conn = getDbConnection();


// 1 - remove first comma from genre
$start_comma_query = "UPDATE artist_profile set genre=substr(genre,2) where trim(genre) like ',%';";
$start_comma_statement = $conn->prepare($start_comma_query);
$start_comma_statement->execute();
$start_comma_count = $start_comma_statement->rowCount();
echo '<pre>';
print_r("rows with first comma (,) removed = ".$start_comma_count);


// 2 - replace genre names with genre ids
$replace_genre_query = "SELECT * FROM artist_profile WHERE genre REGEXP '^[A-Za-z]';";
$replace_genre = $conn->prepare($replace_genre_query);
$replace_genre->setFetchMode(PDO::FETCH_ASSOC);
$replace_genre->execute();
$replace_genre_result = $replace_genre->FETCHALL();

$genre_list_query = "SELECT * FROM genres";
$genre_list = $conn->prepare($genre_list_query);
$genre_list->setFetchMode(PDO::FETCH_ASSOC);
$genre_list->execute();
$genre_list_result = $genre_list->FETCHALL();

foreach ($replace_genre_result as $key => $value) {
	$genre_array = explode(',', $value['genre']);

	$genre_id_array = array();
	foreach ($genre_array as $genre_key => $genre_value) {
		if($genre_value == 'Aduma'){
			$genre_value = 'Aduma (Kenya)';
		}
		$genre_id_index = array_search($genre_value, array_column($genre_list_result, 'genre_name'));
		$genre_id_array[] = $genre_list_result[$genre_id_index]['genre_id'];
	}
	$genre = implode(',', $genre_id_array);
	$genre_update_query = "UPDATE artist_profile set genre= '".$genre."' where artist_profile_id = ".$value['artist_profile_id'];
	echo '<pre>';
	print_r($genre_update_query);
	$genre_update_statement = $conn->prepare($genre_update_query);
	$genre_update_statement->execute();
}
?>