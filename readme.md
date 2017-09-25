#Contempo_RSVP

## an easy event management rsvp tool for Contempo Media.

This is a simple event tool  

### Some requirements

* Automatically approve guests whose emails are on our approve guestlist, add them to db table and send them a confirmation email
* Pull in more information about the user from our guestlist and store in db table 
* Add unknown RSVPs to a separate table
* Allow Contempo staff members to view unknown rsvps and either approve or deny them for the event
* If desired, let anyone rsvp for an email
* Create a reusable and maintainable codebase and front-end template

## How to Use

1. Clone the repo

2. Rename the sql files. Replace the **** with corresponding event name

3. Update the config-sample.php file found in the _inc directory
- add the appropriate database info
- update db table names to match the sql files editted previously
- update basepath to match url folder on server
- update event confirmation emails, subject lines, hosts, emails, etc.

4. Open event-info.php
- add pertinent event info based off of invite design

5. Style page as neccessary
- open main.scss in the styles directory
- edit sass files as needed
- In Terminal, run the Gulp command to compile sass into css

6. Update confirmation emails
-  in src/emails.php, make sure email functions are sending the appropriate copy to the guests and to staff
- Is the correct staff memebers receiving the unknown notifications, if applicable?

7. Prepare list
- Do the columns match the columns in validate.php? 
- export csv as Windows CSV in excel. If the file is not formatted this way, php will not be able to read it properly and the RSPV Match function will break.


