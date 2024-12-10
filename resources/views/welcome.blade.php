<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Live Streaming Platform</title>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://unpkg.com/livekit-client@2.x/dist/livekit-client.js"></script>
    <style>
        #local-video, #remote-videos {
            display: flex;
            flex-wrap: wrap;
        }
        video {
            max-width: 300px;
            margin: 10px;
        }
    </style>
</head>
<body>
    <div id="stream-setup">
        <input type="text" id="username" placeholder="Your Username">
        <input type="text" id="room-name" placeholder="Room Name">
        <button id="start-stream">Start Stream</button>
        <button id="join-stream">Join Stream</button>
    </div>

    <div id="stream-container">
        <div id="local-video"></div>
        <div id="remote-videos"></div>
    </div>

    <script>
    $(document).ready(function() {
        let room;
        const localVideoContainer = $('#local-video');
        const remoteVideoContainer = $('#remote-videos');

        $('#start-stream').on('click', function() {
            const username = $('#username').val();
            const roomName = $('#room-name').val();

            $.ajax({
                url: '/streams/start',
                method: 'POST',
                data: {
                    identity: username,
                    room_name: roomName
                },
                success: async function(response) {
                    await initializeStream(response.token, roomName, username, true);
                }
            });
        });

        $('#join-stream').on('click', function() {
            const username = $('#username').val();
            const roomName = $('#room-name').val();

            $.ajax({
                url: '/streams/join',
                method: 'POST',
                data: {
                    identity: username,
                    room_name: roomName
                },
                success: async function(response) {
                    await initializeStream(response.token, roomName, username, false);
                }
            });
        });

        async function initializeStream(token, roomName, username, isPublisher) {
            room = new LivekitClient.Room();

            room.on(LivekitClient.RoomEvent.TrackPublished, async (track, publication, participant) => {
                if (track.kind === 'video') {
                    const videoElement = await track.attach();
                    remoteVideoContainer.append(videoElement);
                }
            });

            room.on(LivekitClient.RoomEvent.TrackUnpublished, (track, publication, participant) => {
                $(`#${track.sid}`).remove();
            });

            await room.connect(`wss://127.0.0.1:7880`, token);

            if (isPublisher) {
                const localTracks = await LivekitClient.createLocalTracks({
                    video: true,
                    audio: true
                });

                localTracks.forEach(track => {
                    room.localParticipant.publishTrack(track);
                    const videoElement = track.attach();
                    localVideoContainer.append(videoElement);
                });
            }
        }
    });
    </script>
</body>
</html>
