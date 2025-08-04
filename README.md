*STDC Attendance System*

  A comprehensive, web-based attendance management system designed for educational
  institutions. It leverages QR codes for a modern, efficient, and paperless approach to
  tracking student attendance.

  1) Key Features

   * QR Code-Based Attendance: Generate unique QR codes for classes or events. Students can
     scan the code to mark their attendance instantly.
   * Multiple User Roles: The system is built with a clear separation of roles and
     permissions:
       * Admin: Manages the entire system, including creating classes, subjects, teachers,
         and students.
       * HEA (Hal Ehwal Akademik): Can view attendance records, manage students, and
         generate reports.
       * HEP (Hal Ehwal Pelajar): Has similar permissions to HEA, likely focused on
         specific programs.
       * Student: Can view their own attendance records and profile.
   * Class & Student Management: Admins can easily create and manage classes, class arms
     (sections), and student profiles.
   * Session & Term Management: Define academic sessions and terms to organize attendance
     records chronologically.
   * Attendance Reporting: View detailed attendance records by class, student, or date range.
   * Data Export: Download attendance records in spreadsheet format for archival or analysis
     (utilizing the PhpSpreadsheet library).

  2) Technology Stack

   * Backend: PHP
   * Frontend: HTML, CSS, JavaScript, jQuery, Bootstrap
   * Database: MySQL / MariaDB (SQL dump provided)
   * Dependency Management: Composer
   * Key Libraries:
       * phpqrcode: For generating the QR codes.
       * phpoffice/phpspreadsheet: For exporting data to Excel format.

  3) Project Structure

  The application is organized into modules based on user roles:

   * /Admin: Admin dashboard and management functionalities.
   * /HEA: Portal for the Hal Ehwal Akademik.
   * /HEP: Portal for the Hal Ehwal Pelajar.
   * /Student: Portal for students to view their attendance.
   * /Includes: Contains shared files like database connection (dbcon.php) and session
     management.
   * /vendor: Composer dependencies.
   * /DATABASE FILE: Contains the SQL file (attendancesystem.sql) to set up the database
     schema and initial data.
     
  4)  Project Preview

 <img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/546667e9-9d96-4f87-bcaa-fd8f310ea06f" />
 <br>
 <img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/2f0deac1-671c-46ac-878d-a8c4cf78796c" />
 <br>
 <img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/9aa3eb46-96a7-4320-8a56-cf6bbe6a3afe" />
 <br>
 <img width="800" height="600" alt="image" src="https://github.com/user-attachments/assets/68f2e150-9cfb-479a-8fea-10fad9d17523" />







