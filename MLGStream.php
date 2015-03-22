<?php

class MLGStream {
    // API
    private $URL_ENDPOINT       = "http://streamapi.majorleaguegaming.com/service/streams/";
    private $URL_ALL            = "http://www.majorleaguegaming.com/api/channels/all.js";
    private $URL_STATUS         = "http://streamapi.majorleaguegaming.com/service/streams/status/";
    private $URL_CHAT           = "http://chat.majorleaguegaming.com/";
    private $URL_EMBED          = "http://tv.majorleaguegaming.com/player/embed/";

    // Channel info
    private $ID_CHANNEL;        // 82
    private $ID_MLG;            // mlg15
    private $STREAM_USERNAME;   // NaDeSHoT
    private $STREAM_SLUG;       // nadeshot
    private $STREAM_FRIENDLY;   // OpTic NaDeSHoT
    private $STREAM_TITLE;      // "OpTic Scrims"
    private $STREAM_DESC;       // "OpTic Nadeshot is a..."
    private $STREAM_OBJECT;     // Raw JSON
    private $SOCIAL_INSTA;      // nadeshot
    private $SOCIAL_FACEB;      // nadeshot
    private $SOCIAL_TWITT;      // OpTic_NaDeSHoT
    private $SOCIAL_YOUTU;      // opticnade
    private $SOCIAL_URL;        // http://tv.majorleaguegaming.com/channel/nadeshot

    function __construct($username)
    {
        $this->STREAM_USERNAME = $username;
    }

    private function verifyRequest($request)
    {
        if (is_null($request))
        {
            throw new Exception('null response');
        } else {
            $code = $request->status_code;
            if ($code != '200')
            {
                throw new Exception('status code != 200');
            }
        }
    }

    public function refresh($filter)
    {
        $request = json_decode(@file_get_contents($this->getStreamsUrl($filter)));
        $this->verifyRequest($request);

        $items = $request->data->items;

        foreach ($items as $channel)
        {
            $lower = strtolower($this->getUsername());
            if ($channel->slug == $lower)
            {
                $this->ID_CHANNEL       = $channel->id;
                $this->ID_MLG           = $channel->stream_name;
                $this->STREAM_SLUG      = $channel->slug;
                $this->STREAM_FRIENDLY  = $channel->name;
                $this->STREAM_TITLE     = $channel->subtitle;
                $this->STREAM_DESC      = $channel->description;
                $this->STREAM_OBJECT    = $channel;
                $this->SOCIAL_INSTA     = $channel->social_instagram;
                $this->SOCIAL_FACEB     = $channel->social_facebook;
                $this->SOCIAL_TWITT     = $channel->social_twitter;
                $this->SOCIAL_YOUTU     = $channel->social_youtube;
                $this->SOCIAL_URL       = $channel->url;
            }
        }
    }

    /*
     *  Social data
     */
    // Live checking if the stream is online
    public function isOnline()
    {
        $request = json_decode(@file_get_contents($this->URL_STATUS . $this->ID_MLG));
        $this->verifyRequest($request);

        $status = $request->data->status;
        if ($status == -1)
        {
            return false;
        } else {
            return true;
        }
    }
    // Returns the live total of viewers
    public function getViewers()
    {
        $request = json_decode(@file_get_contents($this->URL_STATUS . $this->ID_MLG));
        $this->verifyRequest($request);

        return $request->data->viewers;
    }
    // Returns the users' Instagram handle
    public function getInstagram()
    {
        return $this->SOCIAL_INSTA;
    }
    // Returns the users' Facebook handle
    public function getFacebook()
    {
        return $this->SOCIAL_FACEB;
    }
    // Returns the users' Twitter handle
    public function getTwitter()
    {
        return $this->SOCIAL_TWITT;
    }
    // Returns the users' YouTube handle
    public function getYouTube()
    {
        return $this->SOCIAL_YOUTU;
    }
    // Returns the friendly URL to the stream
    public function getUrl()
    {
        return $this->SOCIAL_URL;
    }

    /*
     *  Embed data
     */
    // Returns the embed URL
    public function getEmbedUrl()
    {
        return $this->URL_EMBED . $this->STREAM_USERNAME;
    }

    // Currently useless as MLG blocks it
    public function getChatUrl()
    {
        return $this->URL_CHAT . $this->ID_CHANNEL;
    }

    /*
     *  Internal data
     */
    // true     = filter important info, faster
    // false    = raw response to access everything, slower
    private function getStreamsUrl($filter)
    {
        if ($filter)
        {
            return $this->URL_ALL . '?fields=id,slug,name,stream_name,subtitle,description,social_instagram,social_facebook,social_twitter,social_youtube,url';
        } else {
            return $this->URL_ALL;
        }
    }
    // Use this to access the raw response
    public function getStreamObject()
    {
        return $this->STREAM_OBJECT;
    }
    // Username operations
    // Changing this before a refresh() will change the stream
    public function setUsername($username)
    {
        $this->STREAM_USERNAME = $username;
    }
    public function getUsername()
    {
        return $this->STREAM_USERNAME;
    }

    // Slug operations
    // Changing this may cause conflicts
    public function setSlug()
    {
        $this->STREAM_SLUG = strtolower($this->STREAM_USERNAME);
    }
    public function getSlug()
    {
        return $this->STREAM_SLUG;
    }

    // MLG ID operations (internal stream name)
    // Changing this may cause conflicts
    public function setMlgId($id)
    {
        $this->ID_MLG = $id;
    }
    public function getMlgId()
    {
        return $this->ID_MLG;
    }

    // Channel ID operations (internal stream identifier)
    // Changing this may cause conflicts
    public function setChannelId($id)
    {
        $this->ID_CHANNEL = $id;
    }
    public function getChannelId()
    {
        return $this->ID_CHANNEL;
    }

    // Channel ID operations (internal stream identifier)
    // Changing this may cause conflicts
    public function setDescription($desc)
    {
        $this->STREAM_DESC = $desc;
    }
    public function getDescription()
    {
        return $this->STREAM_DESC;
    }
} 