STDC Attendance System

  A comprehensive, web-based attendance management system designed for educational
  institutions. It leverages QR codes for a modern, efficient, and paperless approach to
  tracking student attendance.

  Key Features


   * QR Code-Based Attendance: Generate unique QR codes for classes or events. Students can
     scan the code to mark their attendance instantly.
   * Multiple User Roles: The system is built with a clear separation of roles and
     permissions:
       * Admin: Manages the entire system, including creating classes, subjects, teachers,
         and students.
       * HEA (Head of Education Affair): Can view attendance records, manage students, and
         generate reports.
       * HEP (Head of Education Programme): Has similar permissions to HEA, likely focused on
         specific programs.
       * Student: Can view their own attendance records and profile.
   * Class & Student Management: Admins can easily create and manage classes, class arms
     (sections), and student profiles.
   * Session & Term Management: Define academic sessions and terms to organize attendance
     records chronologically.
   * Attendance Reporting: View detailed attendance records by class, student, or date range.
   * Data Export: Download attendance records in spreadsheet format for archival or analysis
     (utilizing the PhpSpreadsheet library).

  Technology Stack


   * Backend: PHP
   * Frontend: HTML, CSS, JavaScript, jQuery, Bootstrap
   * Database: MySQL / MariaDB (SQL dump provided)
   * Dependency Management: Composer
   * Key Libraries:
       * phpqrcode: For generating the QR codes.
       * phpoffice/phpspreadsheet: For exporting data to Excel format.

  Project Structure

  The application is organized into modules based on user roles:


   * /Admin: Admin dashboard and management functionalities.
   * /HEA: Portal for the Head of Education Affair.
   * /HEP: Portal for the Head of Education Programme.
   * /Student: Portal for students to view their attendance.
   * /Includes: Contains shared files like database connection (dbcon.php) and session
     management.
   * /vendor: Composer dependencies.
   * /DATABASE FILE: Contains the SQL file (attendancesystem.sql) to set up the database
     schema and initial data.

  Installation

   1. Clone the repository:


   1     git clone <your-repo-url>

   2. Database Setup:
       * Create a new MySQL/MariaDB database.
       * Import the DATABASE FILE/attendancesystem.sql file into your new database.
   3. Install Dependencies:
       * Ensure you have Composer (https://getcomposer.org/) installed.
       * Run composer install in the project root to install the required PHP packages.
   4. Configure Database:
       * Open the Includes/dbcon.php file.
       * Update the database host, username, password, and database name with your
         credentials.
   5. Run the Application:
       * Place the project folder in your web server's root directory (e.g., htdocs for
         XAMPP, www for WAMP).
       * Open your web browser and navigate to the project's URL (e.g.,
         http://localhost/STDC-Attendance-System).
