<?php
/**
 * uncomment to test !
 *
 *
 *
 *
 */

require('ThePlaylist.class.php');
$p = new ThePlaylist();

/*
// INSERT VIDEOS
$videos_liste = array(
array( 'title' => 'Les vacances de Oui Oui',
			     'desc' => 'Oui Oui fait un voyage avec ses amis',
			     'thumb' => 'vacouioui'),

array( 'title' => 'Pat Metheny en concert',
			     'desc' => 'Concert du formidable guitariste de jazz Pat Metheny',
			     'thumb' => 'patmetheny1'),

array( 'title' => 'John Scofield au Nes Morning',
			     'desc' => 'Concert formidable de John Scofield au New Morning',
			     'thumb' => 'scomorning'),

array( 'title' => 'Opération Football',
			     'desc' => 'Une opération de promotion du footballl par France Football',
			     'thumb' => 'footopsz'),

array( 'title' => 'Chant de l\'ours',
			     'desc' => 'Chant de l\'ours dans une fôret canadienne',
			     'thumb' => 'ourstehgdsi'),

array( 'title' => 'Bateau de plaisance',
			     'desc' => 'Un bateau de plaisance sans capitaine',
			     'thumb' => 'batedskjfz'),

array( 'title' => 'Parisien en vacances',
			     'desc' => 'Une vidéo d\'un parisien en vacacnes en moldavie',
			     'thumb' => 'vacouioui'),

array( 'title' => 'Embouteillage',
			     'desc' => 'Je me suis filmé dans les emboutaillage ce matin',
			     'thumb' => 'enbmdjsr'),

array( 'title' => 'Chat qui joue avec une pelote',
			     'desc' => 'La vidéo de mon chat en train de jouer avec une pelote de laine',
			     'thumb' => 'chatpelote'),

array( 'title' => 'Vol d\'oiseaux',
			     'desc' => 'Un vol d\'oiseaux au Nicaragua',
			     'thumb' => 'voloiseanss'),
);			     

// Insertion des videos
foreach( $videos_liste as $v ){
	if( $id = $p->insertVideo( $v ) ) echo '<p>Video num : ' . $id. '</p>';
}

// INSERT PLAYLITS
$id_user = '1';			     
$playlists = array( array(  'playlist_name' => 'Les chats', 
			   		        'playlist_desc' => 'Quelques videos sur les chats'), 
			    	array(  'playlist_name' => 'Musique', 
			   		   		'playlist_desc' => 'Mes mucisiens préféres'),
			    	array(  'playlist_name' => 'Animaux', 
			   		   		'playlist_desc' => 'Vidéos sur les animaux'),
			    	array(  'playlist_name' => 'Voiture', 
			   		   		'playlist_desc' => 'Films sur les voitures'),		   
					); 
// créer un playlist
foreach($playlists as $d ) {
	$i = $p->createPlaylist($id_user, $d); 
	if( $i ) echo '<h2> My PLaylist '. $d['playlist_name'].' is created <br />
	ID Playlist : ' . $i . '</h2>';
}

// ADD VIDEO TO PLAYLIST
// Ajouter une video dans ma playlist Chats
$p->addVideoToPlaylist('3', '1');
// Ajouter des videos dans ma playlist Guitariste de jazz
$p->addVideoToPlaylist('5', '9');
// Ajouter des videos dans ma playlist Chat
$p->addVideoToPlaylist('3', '3');
// Ajouter des videos dans ma playlist voiture 1
$p->addVideoToPlaylist('4', '6');
// Ajouter des videos dans ma playlist voiture 1
$p->addVideoToPlaylist('5', '4');
// Ajouter des videos dans ma playlist voiture 1
$p->addVideoToPlaylist('5', '6');
// Ajouter des videos dans ma playlist voiture 1
$p->addVideoToPlaylist('5', '10');
*/


// Remove a video from a playlist
$id_playlist = ''; 
$id_video = '';
//$result = $p->deletePlaylistVideo( $id_playlist, $id_video  );


//////////////////////////////////////////

// Get All Video
$videos = $p->getAllVideo(); 
//$datas = $p->utf8EncodeArray( $videos );

// GET ALL PLAYLIST
//$datas = $p->getAllPlaylist(); 

// Return the list of all videos from a playlist 
$id_playlist = 5;
//$datas = $p->getPlaylistVideo( $id_playlist );


// Playlist Information
$id = 1;
$datas = $p->getPlaylist( $id );


//DELETE A PLAYLIST
$id_playlist = '';
if( is_numeric( $id_playlist ) ) {
	$n = $p->deletePlaylist( $id_playlist ); 
	echo '<h3>'.$n.' Enregistrement supprimé</h3>';
}


/*
// UPADATE PLAYLIST
$data = ['playlist_name' => 'Chanson Française', 'playlist_desc' => 'Les chansons françaises que j\'adore' ];
$id = 1;
$p->updatePlaylist($id, $data);
*/

////////////////////////////////////////



$p->closeDBConnection();

/*
echo '<pre>';
print_r(  $datas );
echo '</pre>';
*/

//header("Content-Type: application/json");
//echo json_encode( $datas );
?>