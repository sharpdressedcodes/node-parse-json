
var DEFAULT_PORT = 8080;
var http = require('http');

var server = http.createServer(createServer);
server.listen(DEFAULT_PORT);

console.log('Listening on port ' + DEFAULT_PORT + '...');

function createServer(request, response){

    if (request.method === 'POST'){
        parsePostRequest(request, response, function(){

            console.log(response.post);

            var result = parseJsonData(response.post);

            if (result.error !== undefined){
                // bad request
                response.writeHead(400, {"Content-Type": "application/json"});
            } else {
                response.writeHead(200, "OK", {"Content-Type": "application/json"});
            }

            response.write(JSON.stringify(result));
            response.end();

        });
    } else {
        // method not allowed
        response.writeHead(405, {"Content-Type": "text/plain"}).end();
        request.connection.destroy();
    }

}

function parsePostRequest(request, response, callback){

    if (typeof callback !== 'function'){
        console.log('Callback not defined!');
        return;
    }

    var buffer = '';

    request.on('data', function(data){
       buffer += data;
        if (buffer.length > 1e6){
            buffer = '';
            // request entity too large
            response.writeHead(413, {"Content-Type": "text/plain"}).end();
            request.connection.destroy();
        }
    });

    request.on('end', function(){
        response.post = buffer;
        callback();
    });

}

function parseJsonData(data){

    var response = null;

    try {

        data = JSON.parse(data);

        var shows = [];
        var showCount = 0;
        var payload = data.payload;

        for (var i = 0; i < payload.length; i++){
            var item = payload[i];

            if (item.drm == undefined ||
                item.episodeCount == undefined ||
                isNaN(item.episodeCount) ||
                item.slug == undefined ||
                item.title == undefined ||
                item.image == undefined ||
                item.image.showImage == undefined)
                continue;

            if (item.drm === true && item.episodeCount > 0){
                shows[showCount++] = {
                    "image": item.image.showImage,
                    "slug": item.slug,
                    "title": item.title
                };
            }
        }

        response = {"response": shows};

    } catch (err){
        response = {"error": "Could not decode request: JSON parsing failed"};
    }

    return response;

}