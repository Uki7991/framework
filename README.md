cp .env.example .env

docker-compose up -d --build

http available in 8000 port

### Routes:
- http://localhost:8000/api/tasks [POST] - Create a task
- http://localhost:8000/api/tasks/:id [GET] - Get exact task by id
- http://localhost:8000/api/get-task?task_id=:id [GET] - Get exact task by id
- http://localhost:8000/api/tasks [GET] - All resources


### Hierarchy:
- app - Core
- src - Client code
- helpers
- public - http entrypoint
- console.php - console entrypoint
