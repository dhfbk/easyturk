# EasyTurk

EasyTurk is a web interface that helps user (and, in particular, researchers) to easily submit tasks to [Amazon Mechanical Turk](https://www.mturk.com/).
In particular, EasyTurk adds two features that are not included out-of-the-box in the original Amazon tool, but that can be implemented using the APIs:

* Merging more than one record, that need to be annotated, in a single HIT, overtaking the limitation of the official interface.
* Possibility to specify a gold standard question for each HIT, and specifying the behavior of the system w.r.t. the answer given to that question.
* Automatic rejection of HITs where the gold standard question has been mistaken by the worker.
* Possibility to block (or disable) a worker when an answer is given too quickly.
* Possibility to block (or disable) a worker when their long-term performance is below a certain threshold.

## AWS configuration

* Login to the [AWS console](https://aws.amazon.com/it/console/).
* Go to Simple Notification Service.
* Create new subscription pointing to the `sns.php` file on your server (see below).
* In the [Mturk Requester developer console](https://requester.mturk.com/developer), link your Amazon account and create access keys.

## Database configuration

* Create a MySQL user and database accessible to the user.
* In the `inc` folder, copy `config-sample.php` into `config.php` and fill the variables with the database information.
* Open `mturk.sql` file, and replace `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, and `ARN_CODE` with the corresponding values, taken from the AWS website.
* In `mturk.sql` one can also replace:
  - `username` (default `user`)
  - `password` (default `password`, saved in the database using MD5)
  - `region_name` (default `us-east-1`)
  - `use_sandbox`, a boolean value to set whether the user will use the Mturk sandbox (default `1`, meaning "yes").
* Load `mturk.sql` into MySQL, using the above-created user.

## Running the php server

Folders `api`, `server`, and `inc` must be copied to a server where php is installed and the database is reachable. The folder `api` must be accessible from the web (e.g. using Apache or Ngnix web servers). All the API commands are managed by the `api/index.php` file.
The `server/server.php` file has to be executed as a deamon. It is used to submit the HITs and update the database when the workers answer

## Web interface setup

From the root of the project, run `npm install` and `npm run serve`. Then go to `http://localhost:8080` and login with credentials `user` and `password` (if not changed in the previous steps).

### Compiles and minifies for production

Run `npm run build` to compiles the project for production.
