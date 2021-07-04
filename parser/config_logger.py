dictLogConfig = {
  "version":1,
  "handlers":{
    "fileHandler":{
      "class":"logging.FileHandler",
      "formatter":"myFormatter",
      "filename":"parser.log"
    }
  },
  "loggers":{
    "exampleApp":{
      "handlers":["fileHandler"],
      "level":"DEBUG",
    }
  },
  "formatters":{
    "myFormatter":{
      "format":"%(asctime)s %(filename)s - %(threadName)s - %(levelname)s:%(message)s"
    }
  }
}