/*Jai Khanna DBWS FALL 2017*/

/*basic user information*/
CREATE TABLE jzusers(
  user_ID INT NOT NULL AUTO_INCREMENT,
  first_name CHAR(40),
  last_name CHAR(40),
  username VARCHAR(40) NOT NULL UNIQUE,
  email VARCHAR(255),
  gender CHAR(40),
  city CHAR(40),
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  password VARCHAR(255) NOT NULL,
  PRIMARY KEY(user_ID)
);

/*table of genres. spotify id might be retreivable through api*/
CREATE TABLE jzgenres(
  genre_ID INT NOT NULL AUTO_INCREMENT,
  genre_name CHAR(40) UNIQUE,
  spotify_genre_ID CHAR(40)
  PRIMARY KEY(genre_ID)
);

/*table of instruments*/
CREATE TABLE jzinstruments(
  instrument_ID INT NOT NULL AUTO_INCREMENT,
  instrument_name CHAR(40) UNIQUE,
  PRIMARY KEY(instrument_ID)
);

/*table of artists with spotify id*/
CREATE TABLE jzartists(
  artist_ID INT NOT NULL AUTO_INCREMENT,
  artist_name CHAR(40),
  spotify_artist_ID CHAR(40)
  PRIMARY KEY(artist_ID)
 
);

/*genre preferences*/
CREATE TABLE genrepref(
  user_ID INT,
  genre_ID INT,
  PRIMARY KEY(user_ID, genre_ID),
  FOREIGN KEY(user_ID) REFERENCES jzusers(user_ID),
  FOREIGN KEY(genre_ID) REFERENCES jzgenres(genre_ID)
);

/*artist preferences*/
CREATE TABLE artistpref(
  user_ID INT,
  artist_ID INT,
  PRIMARY KEY(user_ID, artist_ID),
  FOREIGN KEY(user_ID) REFERENCES jzusers(user_ID),
  FOREIGN KEY(artist_ID) REFERENCES jzartists(artist_ID)
);

/*instruments played*/
CREATE TABLE playsinstrument(
  user_ID INT,
  instrument_ID INT,
  PRIMARY KEY(user_ID, instrument_ID),
  FOREIGN KEY(user_ID) REFERENCES jzusers(user_ID),
  FOREIGN KEY(instrument_ID) REFERENCES jzinstruments(instrument_ID)
);

/*friends*/
CREATE TABLE jzfriends(
  user_ID INT NOT NULL REFERENCES jzusers(user_ID),
  friend_ID INT NOT NULL REFERENCES jzusers(user_ID),
  PRIMARY KEY(user_ID, friend_ID)

);
