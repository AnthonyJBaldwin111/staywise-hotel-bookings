StayWise is a PHP and MySQL hotel booking web app that lets users browse hotels and rooms, view room details and amenities, search availability by date, and place bookings that calculate total cost and store booking plus payment records.


StayWise is a multi page PHP application backed by a MySQL database. It uses a simple, clean flow where users can explore listings publicly, then use a private account area to manage bookings.

How it works, step by step

Home page (index.php)
The site loads the newest hotels from the database and displays them as cards with images and links to explore.

View hotels (hotels.php)
This page lists all hotels from the hotels table and links you into the room listings.

Browse rooms (rooms.php)
The app pulls active rooms and joins hotel names so you can see which hotel each room belongs to. Each room card links to a detail page.

Room details (room.php)
When you open a room, the app looks up the room by its slug, shows the description, price per night, and pulls amenities through the room_amenities junction table. From here you can click Book now.

Book a room (booking.php)
You enter check in and check out dates. The app calculates the number of nights using DateTime difference, multiplies nights by the room price, then inserts a new record into bookings and a matching record into payments. After that, it shows a confirmation message with the booking ID and total.

Important note: booking.php is currently using a demo user object instead of the logged in session user. In a production version, it should attach the booking to the authenticated user from the session.

Private booking portal (login.php, register.php, bookings.php, logout.php)
This is the private side of the app.

Register (register.php)

User submits name, email, password

Password is hashed with bcrypt

User is inserted into the users table with the guest role

Login (login.php)

User submits email and password

App fetches the user record and role

password_verify checks the password against the stored hash

If valid, the user is stored in the session so pages can treat them as signed in

My bookings (bookings.php)

Pulls bookings for the user and joins rooms and hotels to show what they booked, dates, nights, status, and total

Right now it is set to a demo user_id of 2, so it will always show that user’s bookings unless you switch it to use the session user

Logout (logout.php)

Clears the session and ends the login state

Search availability (search.php)
The user enters check in and check out dates. The app returns rooms that are active and not already booked for overlapping dates, using a NOT IN query against the bookings table.

