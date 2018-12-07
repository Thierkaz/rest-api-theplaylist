<?php
/**
 *
 *
 *
 *
 *
 *
 *
 *
 */

define('DB_SERVER' , 'localhost');
define('DB_NAME', 'the_playlist');
define('DB_USER', 'root');
define('DB_PSSWD', 'root');

/**
 * 
 * 
 */
class ThePlaylist {

	private $con;// la connexion mysql en cours
	private $result; // le resultat de la requete executee
	protected $playlist;
	protected $video;

	/**
	 *  we create a connection with the object
	 *
	 */
	public function __construct() {	
		if( $this->con == NULL || emtpy( $this->con ) ) {
			$this->con = mysqli_connect(DB_SERVER,DB_USER,DB_PSSWD,DB_NAME);

			// Check connection
			if (mysqli_connect_errno()) {
				echo "Failed to connect to MySQL: " . mysqli_connect_error();
			}
		}	
	}


	/**
	 * Execute a query
	 *
	 * @param requete
	 * @return : 
	 */
	public  function mysqlQuery( $sql )
	{ 
		return $this->result = mysqli_query( $this->con, $sql);
	}	
	 

	/**
	 * 
	 * @return (integer) le nombre d'elements modifies par la requete
	 */
	public function getNumRows()
	{
		return mysqli_affected_rows($this->con);
	}

	/**
	* 
	* @return (integer) 
	*/
	public function lastInsertId() {
		return mysqli_insert_id( $this->con );
	}

	/**
	 *
	 */
	public function getArray( $result = NULL) {
		if ( $this->result !== NULL ) {	
			return  mysqli_fetch_array($this->result) ;
		}
		mysqli_free_result( $this->result );		
	}

    /**
     *	Return datas selected in an array
     *	@return array 
     *
     */
    public function fetchAll() {
        $rows = array();
        while( $row = $this->getArray() ) {
        	$rows[] = $row;
        }      
		mysqli_free_result( $this->result );       
        return $rows;
    }
	
    /**
     * close DB connection
     */
	public function closeDBConnection()
	{
		return mysqli_close($this->con);
	}


	///////////////////////////////////////////////////////////////


	/**
	 *  insert a video  (just for test purpose)
	 *
	 */
	public function insertVideo( $data ) {
		
		if(isset($data['title']) && isset($data['thumb'])) {


			//$title     = mysql_real_escape_string( $data['title'], $this->con );
			//$desc      = mysql_real_escape_string( $data['desc'], $this->con );
			//$thumbnail = mysql_real_escape_string( $data['thumb'], $this->con );
 
			$title     = addslashes($data['title']);
			$desc      = addslashes($data['desc']);
			$thumbnail = addslashes($data['thumb']);

			$sql = ' INSERT INTO video (video_title, video_description, video_thumbnail) 
			         VALUES ( "' . $title .'" , "' . $desc . '", "' . $thumbnail .'" )';
			$result = $this->mysqlQuery( $sql);
			if( ! $result ) {
				return 'Query : '.$sql.' - error : '. mysqli_error($this->con);
			} else {
				$id = $this->lastInsertId(); 	
				return $id;
			}	
		}	
	}


	/**
	 *  Create a playlist
	 *
	 */
	public function createPlaylist($id_user, $data) {
		
		if( is_numeric( $id_user )  && isset($data['playlist_name'])) {
 			$name = $data['playlist_name'];
 			$description = $data['playlist_desc'];			
			$query = 'INSERT INTO playlist (id_user, playlist_name, playlist_desc) 
					  VALUES ( "' . $id_user .'" , "' . $name . '", "' . $description .'" )';
			$result = $this->mysqlQuery( $query );
			if( $result ) {
				return $this->lastInsertId(); 	
			} else {
				return 'Erreur !'. mysqli_error($this->con);		
			}	
		}	
	}

	
	/**
	 * Add a video in a playlist
	 *
	 */
	public function addVideoToPlaylist($id_playlist, $id_video) {
		
		if(is_numeric($id_playlist) && is_numeric($id_video)) {

			$max_position = $this->getPlaylistLastVideoPosition( $id_playlist );
			$new_position = $max_position + 1;
			$result = $this->mysqlQuery( 'INSERT INTO playlist_video (id_playlist, id_video, position) 			   VALUES ("'. $id_playlist .'" , "'. $id_video .'", "'. $new_position .'")'); 

			if( $result ) {
				return TRUE; 	
			} else {
				echo 'Erreur !'. mysqli_error($this->con) . '<br />';
				return FALSE;	
			}	
		}
	}

	/**
	 *  the last video positon value
	 *  @param $id_playlist
	 *	@return integer
	 */
	private function getPlaylistLastVideoPosition( $id_playlist ) {
		$result = $this->mysqlQuery('SELECT MAX(position) AS position FROM playlist_video WHERE id_playlist = "'.$id_playlist.'"');
		$o = $this->getArray( $result );
		$max_position = $o['position'];
		return $max_position;
	}

	/**
	 *	Update informations about the playlist
	 *	@param $id_playlist
	 *	@param $data
	 *	@return int 
	 */
	public function updatePlaylist($id_playlist, $data) {
		$name = $data['playlist_name'];
		$desc = $data['playlist_desc'];
		
		$sql = 'UPDATE playlist SET playlist_name = "'.$name.'", playlist_desc = "'.$desc.'" WHERE id_playlist = "'.$id_playlist.'"'; 
		$result = $this->mysqlQuery( $sql );		
		if($result) {
			return $this->getNumRows();
		} else {
			echo 'Erreur !'. mysqli_error($this->con) . '<br />';
			return FALSE;				
		}
	}

	/**
	 *	Show informations about the playlist
	 *	@param int $id_playlist
	 *	@return array
	 */
	public function getPlaylist( $id_playlist ) {
		$sql = 'SELECT * FROM playlist WHERE id_playlist = "'. $id_playlist .'" LIMIT 1';
		$result = $this->mysqlQuery( $sql ); 
		$playlist_info = $this->getArray( $result );
		return $playlist_info;
	}

	/**
	 *  Return the list of all video
	 *	@return array
	 */
	public function getAllVideo() {	
		$result = $this->mysqlQuery( 'SELECT * FROM video' );		
		return $this->fetchAll();
	}

	/**
	 *	Return the list of all playlists
	 *	@return array
	 */
	public function getAllPlaylist() {
		$result = $this->mysqlQuery( 'SELECT * FROM playlist' );		
		return $this->fetchAll();
	}


	/**
	 *	Return the list of all videos from a playlist (ordered by position):
	 *	@return array
	 */
	public function getPlaylistVideo( $id_playlist ) {
		
		$sql = 'SELECT p.id_playlist, p.position, v.id_video, v.video_title, v.video_description, v.video_thumbnail 
				FROM playlist_video AS p 
				INNER JOIN video AS v ON v.id_video = p.id_video 
				WHERE id_playlist = "'.$id_playlist.'"
				ORDER BY position ASC';	
		$result = $this->mysqlQuery( $sql );		
		return $this->fetchAll();
	}

	/**
	 *	Delete the playlist
	 *	@param $id_playlist
	 *	@return int How many video has been removed from the deleted playlist	
	 */
	public function deletePlaylist( $id_playlist ) {

		if( is_numeric( $id_playlist ) ) {
			$result = $this->mysqlQuery( 'DELETE FROM playlist WHERE id_playlist = "'. $id_playlist .'" LIMIT 1' );
			if( $result ) {

				// Delete also all video associated to the playlist
				$num_rows_affected = $this->deletePlaylistAllVideo( $id_playlist ); 
				return $num_rows_affected;
			} else {
				return 'Erreur !'. mysqli_error($this->con);	
			}	
		}
	}


	/**
	 * Delete all videos from a playlist
	 *
	 */
	private function deletePlaylistAllVideo( $id_playlist ) {		
		if( is_numeric( $id_playlist )  ) {			
			$sql = 'DELETE FROM Playlist_video WHERE id_playlist = "'. $id_playlist .'"';
			$result = $this->mysqlQuery( $sql );	
			if( $result ) {
				return $this->getNumRows();
			}  else {
				return 'Erreur !'. mysqli_error($this->con);		
			}	
		}
	}


	/**
	 * 	Delete a video from a playlist
	 * 	Removing videos re-arrange the order of your playlist and the storage.
	 *	@param
	 *	@param
	 *	@return int should be 1	
	 *
	 */
	public function deletePlaylistVideo( $id_playlist, $id_video ) {	

		if( is_numeric( $id_playlist ) && is_numeric( $id_video ) )  {			
			$position = $this->getPlaylistVideoPosition( $id_playlist, $id_video ); 
			$sql = 'DELETE FROM playlist_video WHERE id_playlist = "'.$id_playlist.'" AND id_video = "'.$id_video.'" LIMIT 1';
			$result = $this->mysqlQuery( $sql );
			$n = $this->getNumRows();			
			if( $result ) {
				$this->updateVideoPosition( $id_playlist, $position );
				return $n;
			} else {
				echo 'Erreur !'. mysqli_error($this->con);		
			}	
		}
	}


	/**
	 *	return a video position in the playlist
	 *	@param int $id_palylist
	 *	@param int $id_video	
	 *	@return int position
	 */
	private function getPlaylistVideoPosition( $id_playlist, $id_video )
	{
		$sql = 'SELECT position FROM playlist_video 
				WHERE id_playlist = "'.$id_playlist.'" 
				AND id_video = "'.$id_video.'"';
		$result = $this->mysqlQuery( $sql );
		$p = $this->getArray( $result );
		return  $p['position'];
	}

	/**
	 *	reorder video list
	 *
	 */
	private function updateVideoPosition( $id_playlist, $position ) {
		$sql = 'UPDATE playlist_video SET position =  position - 1 
		        WHERE id_playlist = "'.$id_playlist.'" 
		        AND position > ' . $position;
		$result = $this->mysqlQuery( $sql );		
		if($result) {
			return $this->getNumRows();
		}else {
			echo 'Erreur !'. mysqli_error($this->con);		
		}	
	}

	/**
	 *	If we need to change an array encodage to utf8 
	 *	@param array
	 *	@return array in utf8
	 */
	public function utf8EncodeArray( $array ) {
		$data = array();
		foreach ( $array as $a ) {
			if( is_array( $a ) ) {
				$data[] = $this->utf8EncodeArray( $a );
			} else {
				$data[] = utf8_encode($a);
			}
		}
		return $data;	
	}	

}	
