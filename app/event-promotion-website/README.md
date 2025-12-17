
## Event Promotion Website

### Project Description
This Event Promotion Website is a web application designed to help users manage and promote events. Built with a localhost server and MySQL using XAMPP, this application allows users to sign up, log in, and log out securely. Logged-in users can add new events, update existing ones with their respective dates, view event details, and delete events. Additionally, users can add artists to the event list, which will be displayed dynamically using AJAX without refreshing the page. Only authenticated users have the privileges to manage events and artists.

### Features
- **User Authentication:**
  - User sign-up, login, and logout.
  - Secure password management.

- **Event Management:**
  - Add new events.
  - Automatically update existing events with new dates.
  - View event details.
  - Delete events.

- **Artist Management:**
  - Add new artists.
  - Display artists dynamically using AJAX without page refresh.

- **User Permissions:**
  - Only logged-in users can add, update, and delete events and artists.

### Technologies Used
- **Backend:** PHP
- **Database:** MySQL (using XAMPP)
- **Frontend:** HTML, CSS, JavaScript, AJAX
- **Libraries and Tools:**
  - Bootstrap for responsive design
  - jQuery for simplified JavaScript operations and AJAX
  - JSON for data interchange with AJAX

### Installation and Setup
1. **Clone the Repository:**
   ```bash
   git clone https://github.com/yourusername/event-promotion-website.git
   cd event-promotion-website
   ```

2. **Setup XAMPP:**
   - Download and install XAMPP from [Apache Friends](https://www.apachefriends.org/index.html).
   - Start Apache and MySQL from the XAMPP Control Panel.

3. **Import the Database:**
   - Open phpMyAdmin from the XAMPP Control Panel.
   - Create a new database (e.g., `event_promotion`).
   - Import the `event_promotion_site.sql` file located in the repository.

4. **Configure the Project:**
   - Open the project in your preferred code editor.
   - Update the database configuration in the `config.php` file with your database credentials.

5. **Run the Project:**
   - Place the project folder in the `htdocs` directory of your XAMPP installation.
   - Access the project by navigating to `http://localhost/event-promotion-website` in your web browser.

### Usage
- **User Registration and Login:**
  - Open the application and navigate to the sign-up page to create a new account.
  - Use your credentials to log in and gain access to event and artist management features.

- **Managing Events:**
  - Add new events through the event management interface.
  - Update event details, including dates.
  - View details of all events.
  - Delete events that are no longer needed.

- **Managing Artists:**
  - Add new artists to the event list.
  - Artists will be displayed dynamically without refreshing the page.

### Contributing
Contributions are welcome! Please fork the repository and create a pull request with your changes. Ensure your code follows the project's style guidelines and includes appropriate tests.

### License
This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.

### Contact
For questions, suggestions, or issues, please open an issue on GitHub or contact at "haleemamalik589@gmail.com" .

