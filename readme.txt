
Installation information.
========================

1/ Import the db/the_playlist.Sql to your databse to create all needed tables


2/ Uncomment src/test.php from line 13 to 97


3/ Run src/test.php to put some tests datas in the tables


4/ Use those URLs to test rest.php : 

Return list of all videos
-------------------------

- GET : rest.php/video 


Return the list of all paylist (not filtered by user)
-----------------------------------------------------

- GET : rest.php/playlist


Return the list of all videos from a playlist ordered by position
-----------------------------------------------------------------

- GET : rest.php/playlist/id_playlist/all 


Show informations about the playlist 
------------------------------------

- GET : rest.php/playlist/id_playlist/info ()


Create a playlist
------------------

- POST : rest.php/playlist/ 


Update informations about the playlist
--------------------------------------

- PUT  : rest.php/playlist/id_playlist 


Add a video in a playlist 
---------------------------

- PUT  : rest.php/playlist/video/id_playlist/id_video


Delete a playlist 
-----------------

- DELETE : rest.php/playlist/id_playlist


Remove a video from a playlist
-------------------------------

- DELETE : rest.php/playlist/id_playlist/video/id_video

  