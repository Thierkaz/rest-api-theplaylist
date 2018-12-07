<?php

require("ThePlaylist.class.php");

set_exception_handler(function ($e) {
	$code = $e->getCode() ?: 400;
	header("Content-Type: application/json", NULL, $code);
	echo json_encode(["error" => $e->getMessage()]);
	exit;
});

// assume JSON, handle requests by verb and path
$verb = $_SERVER['REQUEST_METHOD'];
$url_pieces = explode('/', $_SERVER['PATH_INFO']);

// All tasks we need to execute
$playlist = new ThePlaylist();

// authorized routes
$routes = array('video', 'playlist');

// if non-authorized route
if( !in_array( $url_pieces[1], $routes )  ) {
	throw new Exception('Unknown endpoint', 404);
}

// avoid the displays of a php error before the header() call
// id no result return
$data = array();


switch($verb) {
	
	// Return the list of all videos:
	// url = video
	// Return the list of all playlists:
	// url = playlist
	// Return the list of all videos from a playlist (ordered by position)
	// url = playlist/id_playlist/all	
	// Show informations about the playlist
	// url = playlist/id_playlist/info
	case 'GET':
		if( !isset($url_pieces[2]) ) {
			
			if( $url_pieces[1] == 'video') {

				try {
					// Return the list of all videos:
					$data = $playlist->getAllVideo() ;
				
				} catch (UnexpectedValueException $e) {
					throw new Exception("Resource does not exist", 404);
				}

			} elseif (  $url_pieces[1] == 'playlist' ) {

				try {
					// Return the list of all playlists
					$data = $playlist->getAllPlaylist() ;
				
				} catch (UnexpectedValueException $e) {
					throw new Exception("Resource does not exist", 404);
				}
			} 
		} else {
			
			if (  $url_pieces[1] == 'playlist' && $url_pieces[3] == 'all' ) {

				try {

					// Return the list of all videos from a playlist (ordered by position)
					$data = $playlist->getPlaylistVideo( $url_pieces[2] );
				
				} catch (UnexpectedValueException $e) {
					throw new Exception("Resource does not exist", 404);
				}			

			} elseif (  $url_pieces[1] == 'playlist' && $url_pieces[3] == 'info' ) {

				try {

					// Show informations about the playlist
					$data = $playlist->getPlaylist( $url_pieces[2] );
				
				} catch (UnexpectedValueException $e) {
					throw new Exception("Resource does not exist", 404);
				}			

			} 
		}
		break;

	// Create a playlist
	// URL : playlist/ 	
	case 'POST':
		
		// read the JSON
		$params = json_decode(file_get_contents("php://input"), true);
		if(!$params) {
			throw new Exception("Data missing or invalid");		
		}

		// We create a fake default user id
		$id_user = '5';
		
		// Create a playlist
		$item = $playlist->createPlaylist( $id_user, $params ); 
		$status = 201;	

		// if success
		if( $item > 0) {
			$data = array( 'Result' => 'OK'); 
		}

		// send header, avoid output handler
		//header("Location: " . $item['url'], null, $status);
		//exit;
		break;	

	// Update informations about the playlist
	// URL : playlist/id_playlist 	
	// Add a video in a playlist
	// URL : playlist/video/id_playlist/id_video	
	case 'PUT':

		// Add a video in a playlist
		if ( $url_pieces[1] == 'playlist' && $url_pieces[2] == 'video' ) {

			$id_playlist = $url_pieces[3];
			$id_video = $url_pieces[4];
			$item = $playlist->addVideoToPlaylist($id_playlist, $id_video);
			$status = 204;	
		
		// Update informations about the playlist		
		} else {				
			
			// read the JSON
			$params = json_decode(file_get_contents("php://input"), true);
			if(!$params) {
				throw new Exception("Data missing or invalid");
			}

			$id_playlist = $url_pieces[2];
			$item = $playlist->updatePlaylist($id_playlist, $params);
			$status = 204;		
		}	

		// if success
		if( $item > 0) {
			$data = array( 'Result' => 'OK'); 
		}

		// send header, avoid output handler
		//header("Location: " . $item['url'], null, $status);
		//exit;
		break;
	
	// Delete the playlist
	// url = playlist/id_playlist	
	// Remove a video from a playlist
	// url = playlist/id_playlist/video/id_video 	
	case 'DELETE':
		
		// Delete a video from a playlist
		if( count($url_pieces) > 2 ) {
			$id_playlist = $url_pieces[2];
			$id_video = $url_pieces[4];
			$r = $playlist->deletePlaylistVideo( $id_playlist, $id_video );
			
			// return a message if success
			if( $r ) $data = ['result' => 'Video ' . $id_video. ' removed from playslist : ' . $id_playlist];
		
		//Delete the playlist	
		} else {
			$id_playlist = $url_pieces[2];
			$r = $playlist->deletePlaylist( $id_playlist );

			// return a message id success
			if( $r ) $data = ['result' => 'Playslist' . $id_playlist . ' deleted '];
		}
		break;
	default: 
		$data = $playlist->getAllVideo() ;
		throw new Exception('Method Not Supported', 405);
}

/*
echo '<pre>';
print_r( $data );
echo '</pre>';
*/
$playlist->closeDBConnection();
header("Content-Type: application/json");
echo json_encode($data);