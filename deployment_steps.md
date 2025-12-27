## Running the project with Docker

1.  Make sure you have Docker and Docker Compose installed on your system.
2.  Clone the repository.
3.  Navigate to the project's root directory.
4.  Run the command `docker-compose up -d --build`. This will build the Docker image and start the services.
5.  The application will be available at `http://localhost:8080`.

## Deploying to Render

1.  Create a new Web Service on Render.
2.  Connect your Git repository to Render.
3.  Choose the `render.yaml` file in the settings to define the services.
4.  Render will automatically build and deploy the application.
5.  The database will be initialized with the `DATABASE FILE/attendancesystem.sql` file.
6.  The application will be available at the URL provided by Render.
