# RRC - Web Development 2 Project
## _Goal_

The goal of this assignment is to create a PHP CRUD-based Content Management System (CMS) for a fictional client using a variety of the technologies.

_Description of the CMS Site_
[--] is [--] based not-for-profit organization that posts official motorcycle reviews. Recent studies have shown that there is an increase in motorcycle owners in [--] and the motorcycle community is dispersed to various social media platforms. Gathering consensus on motorcycle in the city is hard to do. This CMS is an opportunity to provide a central reliable location for motorcycle reviews to be found and placed. The proposed CMS will allow users (authenticated and non-authenticated) to review bikes posted on the website. They may also search for bikes by make, model, type, and other criteria. They will also be able to post reviews of bikes they have owned, and rate other users' reviews. Also, the administrator will be capable of posting, updating, and deleting of bike post, and/or their related reviews by users. The
website will have a responsive design that works well on all devices, including mobile phones and tablets.

## _Features_

Here are some of the key features of the website:
1. User Registration and Authentication: Users will be able to create an account on the website and log in securely.
2. Bike Search: Users can search for bikes by make, model, type, and other criteria.
3. Bike Reviews: Users can post reviews of bikes they have owned, and rate other users'
reviews.
4. Admin Dashboard: The website will have an admin dashboard that allows you to manage
users, reviews, and bikes.
5. Responsive Design: The website will have a responsive design that works well on all
devices, including mobile phones and tablets.

## _Database Structure Description_

### BikePost

| Column Name | Data Type | Description |
| ------ | ------ | ------ |
| id | integer | Primary Key for bike post table |
| make | varchar | bike manufacturer/brand |
| model | varchar | motorcycle model |
| year | date | release year of motorcycle |
| engine | varchar | motorcycle engine description |
| displacement_ccm | float | motorcycle displacement |
| image_url | varchar | path to image's storage location |
| date_created | datetime | bike post date created |
| userID | integer | foreign key for user that posted |

### Comments

| Column Name | Data Type | Description |
| ------ | ------ | ------ |
| commentID | integer | Primary Key for comments record |
| content | varchar | the body of comment on post |
| date_created | datetime | Date coment posted |
| is_anonymous | boolean | indicated if comment by authenticated user |
| userID | integer | foreign key for user referenced |
| BikePostID | integer | foreign key for post |

### User

| Column Name | Data Type | Description |
| ------ | ------ | ------ |
| userID | integer | Primary Key for user record |
| username | varchar | used to login into web platform |
| password | varchar | encrypted password of user |
| is_Admin | boolean | distinguishes admin users |

### Brief Description of the relationships
The BikePost table has a one-to-many relationship with the comments table (one BikePost can have many comments), and both BikePost and comments have a many-to-one relationship with users (many BikePost and comments can belong to one user).




