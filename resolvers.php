<?php



include_once "vendor/diversen/redbean-composer/rb.php";
R::setup('sqlite:'.__DIR__.'/db.sqlite');

$roomByName = function ($name) {
    return R::findOne('room', 'name = ?', [$name]);
};

$roomType = [
    'messages' => function ($room) {
        return R::findAll('message', 'room_id = ?', [$room['id']]);
    },
];

$queryType = [
    'rooms' => function () {
        return R::findAll('room');
    },
    'messages' => function ($root, $args) use ($roomByName) {
        $roomName = $args['roomName'];
        $room = $roomByName($roomName);
        $messages = R::find('message', 'room_id = ?', [$room['id']]);

        return $messages;
    },
];

$mutationType = [
    'start' => function ($root, $args) {
        $roomName = $args['roomName'];

        $room = R::dispense('room');
        $room['name'] = $roomName;

        R::store($room);

        return $room;
    },
    'chat' => function ($root, $args) use ($roomByName) {
        $roomName = $args['roomName'];
        $body = $args['body'];

        $room = $roomByName($roomName);

        $message = R::dispense('message');
        $message['roomId'] = $room['id'];
        $message['body'] = $body;
        $message['timestamp'] = new \DateTime();

        R::store($message);

        return $message;
    },
];

return [
    'Room'     => $roomType,
    'Query'    => $queryType,
    'Mutation' => $mutationType,
];