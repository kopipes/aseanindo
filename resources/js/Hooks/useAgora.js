import AgoraRTC from "agora-rtc-sdk-ng"
import { markRaw, reactive } from "vue";
import { useContext } from "./useContext"
import { useSound } from "./useSound";

const sound = useSound()
const page = useContext().page
if (page.environment === 'production') {
     AgoraRTC.setLogLevel(4);
}

export const useAgora = ({ call }) => {
     const state = reactive({
          client: markRaw(AgoraRTC.createClient({ mode: "rtc", codec: "vp8" })),
          localTracks: {
               audioTrack: null
          },
          remoteUsers: {},
          options: {
               appid: null,
               channel: null,
               uid: null,
               token: null,
          },
          mute: false,
          callUuid: null,
          partnerUuid: null,
          partnerStatus: null,
          timerInterval: null,
     })

     const agoraJoinRoom = async () => {
          await state.client.join(
               state.options.appid,
               state.options.channel,
               state.options.token,
               state.options.uid
          )
          state.localTracks.audioTrack = await AgoraRTC.createMicrophoneAudioTrack()
          await state.client.publish([state.localTracks.audioTrack]);
          window.localStreamTrackAgora = state.localTracks

          listenAgoraStateEvent()
     }

     const listenAgoraStateEvent = () => {

          var userJoined = state.client.remoteUsers
          // console.log({userJoined})
          if(userJoined.length){
               subscribeRoom(userJoined[0],'audio')
          }

          state.client.on('user-published', (user, mediaType) => {
               // console.log('USER PUBLISH')
               subscribeRoom(user, mediaType)
          })
          state.client.on('user-joined', (user) => {
          })
          state.client.on("user-unpublished", (user, mediaType) => {
               if (mediaType === "audio") {
                    delete state.remoteUsers[user.uid]
               }
          })

          state.client.on("user-left", (user, reason) => {
               if (reason == "ServerTimeOut" && user.uid == state.partnerUuid) {
                    state.partnerStatus = "disconnect"
                    setTimeout(() => {
                         disconnectCall()
                    }, 10000);
               }
          })

          AgoraRTC.onAutoplayFailed = () => {
               alert("click to start autoplay!");
          };
          AgoraRTC.onMicrophoneChanged = async (changedDevice) => {
               // console.log(changedDevice)
               if (state.localTracks.audioTrack) {
                    const { device } = changedDevice;
                    // When plugging in a device, switch to a device that is newly plugged in.
                    if (changedDevice.state === "ACTIVE") {
                         state.localTracks.audioTrack.setDevice(device.deviceId);
                         // Switch to an existing device when the current device is unplugged.
                    } else if (device.label === state.localTracks.audioTrack.getTrackLabel()) {
                         const microphoneDevice = await AgoraRTC.getMicrophones();
                         if (microphoneDevice && microphoneDevice[0]) {
                              state.localTracks.audioTrack.setDevice(microphoneDevice[0].deviceId);
                         }
                    }
               }
          };
     }

     const disconnectCall = () => {
          if(state.partnerStatus==='disconnect'){
               call.endCall()
          }
     }
     const subscribeRoom = async (user, mediaType) => {
          // console.log('DISINI CUY')
          // console.log(user)
          // console.log(mediaType)

          state.partnerUuid = user.uid;
          state.remoteUsers[user.uid] = user;

          await state.client.subscribe(user, mediaType);
          if (mediaType === "audio") {
               user.audioTrack.play();
          }
     
          state.partnerStatus = "join"
          sound.stopRinging()
          startDurationTiming()
     }

     const startDurationTiming = () => {
          if (call && call.state.status!=='live') {
               const startCall = new Date().getTime()
               clearTimerInterval()
               call.state.timer = '00:00:00'
               state.timerInterval = setInterval(() => {

                    var distance = new Date().getTime() - startCall

                    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)).toString().padStart(2, "0");
                    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)).toString().padStart(2, "0");
                    var seconds = Math.floor((distance % (1000 * 60)) / 1000).toString().padStart(2, "0");

                    call.state.timer = `${hours}:${minutes}:${seconds}`

                    if (distance < 0) {
                         call.state.timer = '-'
                         clearTimerInterval()
                    }
               }, 1000)
               call.state.status = "live"
          }
     }

     const clearTimerInterval = () => {
          if (state.timerInterval) clearInterval(state.timerInterval)
     }

     const joinCall = async ({ token, channel, userId, appId }) => {
          state.options = {
               appid: appId,
               token: token,
               channel: channel,
               uid: Number(userId)
          }
          await agoraJoinRoom()
     }
     
     const toggleMicrophone = (status) =>{
          state.localTracks?.audioTrack?.setEnabled(status);
     }

     const leaveCall = async () => {
          for (var trackName in state.localTracks) {
               var track = state.localTracks[trackName];
               if (track) {
                    track.stop();
                    track.close();
                    state.localTracks[trackName] = undefined;
               }
          }
          for (var nameTrack in window.localStreamTrackAgora) {
               var trackLocal = window.localStreamTrackAgora[nameTrack];
               if (trackLocal) {
                    trackLocal.stop();
                    trackLocal.close();
               }
          }

          state.remoteUsers = {};
          await state.client.leave();
     }


     return {
          state,
          joinCall,
          leaveCall,
          startDurationTiming,
          toggleMicrophone
     }
}