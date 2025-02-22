First, initialize the file structure of the project. Create an index file, a components folder, and an assets folder. Inside the components folder, include files for the header, footer, all functional code, and the configuration file. Additionally, create separate files for the project's sections, such as the Task Manager and Contact Us sections.
for sql to add new task we can put this commond

CREATE TABLE IF NOT EXISTS tasks (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    task_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
