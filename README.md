#Installation guide

## Requirement

1. docker and docker-compose 1.24.0


## Installation 

### From git

1. clone project:

		git clone git@cameleon.univ-lyon1.fr:jspourre/my-api-client-php.git

2. go to the folder:

		cd my-api-client-php
		
###Other way

1. Extract archive
2. Go to the folder extracted

### Build
This commands should be done inside project folder

		docker-compose build
		docker-compose up -d 

### Finishing

We need to go inside php image

		docker exec -it -u dev sf4_php bash

Inside image:		
		
	cd sf4/ 
	./install.sh
	
### Conclusion

Now we can access to the app with http://localhost/