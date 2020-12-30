# EasyTurk

EasyTurk is a web interface that helps user (and, in particular, researchers) to easily submit tasks to [Amazon Mechanical Turk](https://www.mturk.com/).
In particular, EasyTurk adds two features that are not included out-of-the-box in the original Amazon tool, but that can be implemented using the APIs:

* Merging more than one record, that need to be annotated, in a single HIT, overtaking the limitation of the official interface.
* Possibility to specify a gold standard question for each HIT, and specifying the behavior of the system w.r.t. the answer given to that question.
* Automatic rejection of HITs where the gold standard question has been mistaken by the worker.
* Possibility to block (or disable) a worker when an answer is given too quickly.
* Possibility to block (or disable) a worker when their long-term performance is below a certain threshold.

## Configuration

## Running the php server

## Project setup
```
npm install
```

### Compiles and hot-reloads for development
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```
