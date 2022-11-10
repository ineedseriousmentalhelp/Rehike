<?php
namespace Rehike\Util;

class RichShelfUtils {
    public static function reformatResponse($response) {
        if (!isset($response -> onResponseReceivedActions)) return $response;

        $contents = [];
        foreach ($response -> onResponseReceivedActions as $action)
        if (isset($action -> appendContinuationItemsAction -> continuationItems)) 
        foreach ($action -> appendContinuationItemsAction -> continuationItems as $item)
        if (isset($item -> richSectionRenderer -> content -> richShelfRenderer)) {
            $contents[] = self::reformatShelf($item);
        }
        
        return (object) [
            "sectionListRenderer" => (object) [
                "contents" => $contents
            ]
        ];
    }

    public static function reformatShelf($shelf) {
        if (!isset($shelf -> richSectionRenderer -> content -> richShelfRenderer)) return $shelf;

        $richShelf = $shelf -> richSectionRenderer -> content -> richShelfRenderer;
        $response = (object) [];
        $response -> title = $richShelf -> title ?? null;
        $response -> titleAnnotation = $richShelf -> subtitle ?? null;
        $response -> thumbnail = $richShelf -> thumbnail ?? null;
        $response -> endpoint = $richShelf -> endpoint ?? null;
        $response -> menu = $richShelf -> menu ?? null;
        $contents = [];

        foreach($richShelf -> contents as $item)
            $contents[] = self::reformatShelfItem($item);

        $response -> content = (object) [
            "horizontalListRenderer" => (object) [
                "items" => $contents
            ]
        ];

        return (object) [
            "itemSectionRenderer" => (object) [
                "contents" => [
                    (object) [
                        "shelfRenderer" => $response
                    ]
                ]
            ]
        ];

        return $shelf;
    }

    public static function reformatShelfItem($item) {
        if (isset($item -> richItemRenderer -> content)) {
            foreach ($item -> richItemRenderer -> content as $key => $val) {
                $name = "grid" . ucfirst($key);
                return (object) [
                    $name => $val
                ];
            }
        } else {
            return $item;
        }
    }
}