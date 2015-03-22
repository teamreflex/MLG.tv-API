# MLG.tv-API
Simple PHP class for interfacing with the MLG.tv streaming platform.

# License
The MLG.tv API is open-sourced software licensed under the [GPL2 license](http://opensource.org/licenses/GPL-2.0).

# Contact
- [@aRiseKairu](http://twitter.com/aRiseKairu)

# Usage
Initialize an instance of MLGStream using the channel username, then refresh the instance to load everything:

    $stream = new MLGStream('nadeshot');
    $stream->refresh(false);

Refreshing a stream can be slow due to the lack of a proper MLG.tv API. You can pass a boolean into __refresh()__. _false_ loads everything, while _true_ filters and only gives the following objects:
- id
- slug
- name
- stream_name
- subtitle
- description
- social_instagram
- social_facebook
- social_twitter
- social_youtube
- url

Each of these have their own getters, however you can also access them from:

    $stream->getStreamObject()->description;
    
This will get the description by accessing the object itself, rather than from the saved variable.

### isOnline()
    $stream->isOnline();
    
MLG streams have 3 statuses:
- -1 = offline
- 1 = online
- 0 = rebroadcast

The function will return false for -1, and true for 0 and 1. You do not need to refresh before calling this function.

### getViewers()
    $stream->getViewers();
    
Returns the current amount of viewers. You do not need to refresh before calling this function.

### getEmbedUrl()
    $stream->getEmbedUrl();
    
You can put this straight into an iframe and it will embed the stream.

### getChatUrl()
    $stream->getChatUrl();
    
Supposed to return the popout chat, however MLG blocks the chat from being embedded right now.