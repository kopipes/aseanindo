import { io } from "socket.io-client";
import { useAuth } from "./Hooks/useAuth"
const { VITE_SOCKET_URL, VITE_SOCKET_TOKEN } = import.meta.env;

export const socket = io(VITE_SOCKET_URL, {
     transports: ['websocket'],
     upgrade: false,
     auth: {
          token: VITE_SOCKET_TOKEN
     },
});

export const sendBroadcastEvent = ({ clients, channel, body }) => {
     if (socket.connected) {
          socket.emit('send-broadcast', {
               clients,
               channel,
               body
          })
     }
}

export const joinBroadcast = (id) => {
     if (socket.connected) {
          socket.emit('join-broadcast', id)
     }
}

export const leaveBroadcast = (id) => {
     if (socket.connected) {
          socket.emit('leave-broadcast', id)
     }
}


socket.on("connect", () => {
     console.log('connected')
     const userId = useAuth().user_id
     if (userId) joinBroadcast(userId)
});

socket.on("disconnect", () => {
     console.log('dc')
});


// socket.onAny((eventName, ...args) => {
//      console.log({ eventName, args })
// });