import { joinBroadcast, leaveBroadcast, socket } from "../socket"
import { reactive, ref } from "vue"
import { useRequest } from "./useRequest"
import { useContext } from "./useContext"
import { useSound } from "./useSound";

const request = useRequest()
const context = useContext()
const cookie = context.cookie
const sound = useSound();



export const useCall = ({ router, setSession }) => {

     /** ringing , live , busy */
     const state = reactive({
          agora: null,
          session: null,
          timer: null,
          status: null,
          callId: null,
          mute: false,
          sound: sound,
          showRating: false,
          ringingInterval: null,
          agent_id : null,
          joinedCall : false
     })
     const setCallId = (id) => {
          if (!state.callId && id) {
               cookie.set('call_id', id)
               state.callId = id
               joinBroadcast(id)
               context.pageReloadListener.listen()
          }
     }
     const removeCallId = () => {
          const callIdSession = cookie.get('call_id')
          state.agora?.leaveCall()
          if (callIdSession) {
               cookie.del('call_id')
               state.callId = ""
               state.session = "";
               state.timer = ""
               state.status = "";
               state.status = "";
               state.agora = null;
               state.mute = false
               state.ringingInterval = null
               document.getElementById('clear-call-status-state')?.click()
               context.pageReloadListener.destroy()
               leaveBroadcast(callIdSession)
          }
          state.joinedCall = false;
     }

     const inviteAgent = () => {
          if (cookie.get('call_id')) {
               return;
          }
          sound.ringing()
          state.status = "ringing"
          return request.POST(`call/invite-agent/${state.agent_id}`,{
               chat_id : cookie.get('chat_id')
          })
               .then((result) => {
                    cookie.del("incoming-call");
                    cookie.del('ticket_id')
                    /**
                     * Step
                     * 1. If call accepted, agent will triger emit to all connected socket to refress agent list
                     * 2. Validate max ringing time, if limit max ringing still not accept, hit cancel call
                     * 
                     * ====== Sample response result.data =============
                     * call_id: "99f782b1-b3b8-4c40-b6a2-2a2554f8f7cf"
                     * channel_name: "guest_to_dr_haley_reichert"
                     * company_id: "99c02fd2-7094-4064-a85f-e73bdf1cb50b"
                     * call_session : --- secure ---
                     * token: "0064572c58dc2f2491eb5a81b2013bb6957IABPMhlDY4+4OHFO69n2s2/vUkVwd9u4lDy0XqDsphCHEaNVo4deFsONIgASMwAA1qroZAQAAQAWAuhkAwAWAuhkAgAWAuhkBAAWAuhk"
                     * uuid: 3352874239
                     * max_ringing : 30
                     */
                    const callSession = result.data

                    state.session = callSession
                    setCallId(callSession.call_id)

                    listenSocketBroadcastEvent()

                    /**
                     * Interval to validate max ringing
                     */
                    state.ringingInterval = setInterval(() => {
                         if (state.status === 'ringing') {
                              state.agora?.leaveCall()
                              postTriggerCall('cancel')
                              redirectRequestCallBack()
                         }
                         clearInterval(state.ringingInterval)
                    }, callSession.max_ringing * 1000)
               })
               .catch((error) => {
                    // context.showAlert(error.response.data)
                    state.status = "busy"
                    sound.stopRinging()
                    redirectRequestCallBack()
               })
     }

     const redirectRequestCallBack = () => {
          const liveSource = cookie.get('live-source')
          router.push({
               name: "helpdesk-list",
               params: {
                    id: liveSource.id,
                    category: 'callback',
               },
          });
     }

     const postTriggerCall = (trigerAction, callback = null, callbackError = null) => {
          const callId = cookie.get('call_id')
          state.agora?.leaveCall()
          return request.POST(`call/do/${trigerAction}/${callId}`)
               .then((result) => {
                    sound.stopRinging()
                    if (trigerAction !== 'accept') {
                         if (state.ringingInterval) clearInterval(state.ringingInterval)
                         removeCallId()
                    }
                    if (callback) {
                         callback()
                    }
               })
               .catch((error) => {
                    removeCallId()
                    if (callbackError) {
                         callbackError(error)
                    }
               });
     }

     const endCall = (post = true) => {
          /**
           * Step
           * 1. Post End Call
           * 2. If has ticket id and not in chat, show rating popup
           * 3. But i in chat switch to chat
           * 
           * Show rating popup condiiton
           * 1. If do chat only whitout call show popup
           * 2. If chat and call but call still runing, popup not close, only end chat
           * 3. Condition for call is same
           */

          const ticketId = cookie.get('ticket_id')
          const callId = cookie.get('call_id')
          const chatId = cookie.get('chat_id')
          var trigerAction = state.status === 'live' ? 'end' : 'cancel'
          if (state.agora) {
               state.agora.leaveCall()
          }
          if (callId) {
               var action = null
               if (chatId) {
                    /**
                     * If in chat do end call and switch to chat component
                     * - end chat and switch to call session
                     */
                    action = true
                    setSession('chat')
               } else if (!chatId) {
                    if (ticketId) {
                         /**
                          * If already submit ticket and not in call do end chat and show rating popup
                          * - and chat and show rating
                          */
                         state.showRating = true
                         console.log(state.showRating)
                         console.log('SHOW RATING')
                         action = true
                         setTimeout(() => {
                              document.getElementById('button-show-popup-rating-trigger')?.click()
                              document.getElementById('button-show-popup-rating')?.click()
                         }, 200)
                    }
               }

               sound.stopRinging()
               if (post) {
                    if (!action) context.loading.toggle()
                    return postTriggerCall(trigerAction, () => {
                         console.log('leave call')
                         if (state.agora) {
                              state.agora.leaveCall()
                         }
                         if (!action) {
                              cookie.del('ticket_id')
                              context.loading.toggle()
                              router.push({ name: 'product-list' })
                              setTimeout(()=>{
                                   window.location.reload()
                              },1500)
                              return;
                         }
                    }, () => {
                         if (state.agora) {
                              state.agora.leaveCall()
                         }
                         router.push({ name: 'product-list' })
                         cookie.del('ticket_id')
                         setTimeout(()=>{
                              window.location.reload()
                         },1500)
                         return;
                    })
               } else {
                    removeCallId()
                    console.log('leave call')
                    if (state.agora) {
                         state.agora.leaveCall()
                    }
                    if (!action) {
                         cookie.del('ticket_id')
                        router.push({ name: 'product-list' })
                        setTimeout(()=>{
                              window.location.reload()
                         },1500)
                         return;
                    }
               }

          } else if (chatId) {
               setSession('chat')
          } else {
               /**Nothing active session (not in call and chat) just switch page to home list product*/
               router.push({ name: 'product-list' })
               setTimeout(()=>{
                    window.location.reload()
               },1500)
               return;
          }
     }

     const toggleMicrophone = () => {
          state.mute = !state.mute
          state.agora.toggleMicrophone(!state.mute)
     }

     const listenSocketBroadcastEvent = () => {
          // socket.off('BROADCAST')
          socket.on('BROADCAST', async (properties) => {
               const { channel, data } = properties
               const callId = cookie.get('call_id')
               switch (channel) {
                    case "call-accepted":
                         const session = state.session;
                         if (session && !state.joinedCall) {
                              state.joinedCall = true;
                              await state.agora.joinCall({
                                   token: session.token,
                                   channel: session.channel_name,
                                   appId: context.base64decode(session.call_session),
                                   userId: session.uuid.toString()
                              })
                              clearInterval(state.ringingInterval)
                              sound.stopRinging()
                         }
                         break;
                    case "call-rejected":
                         if (data.call_id === callId) {
                              leaveCallByAgent()
                         }
                         break;
                    case "call-cancelled":
                         if (data.call_id === callId) {
                              leaveCallByAgent()
                         }
                         break;
                    case "call-missed":
                         if (data.call_id === callId) {
                              leaveCallByAgent()
                         }
                         break;
                    case "call-ended":
                         if (data.call_id === callId) {
                              state.agora?.leaveCall()
                              endCall(false)
                              state.joinedCall = false;
                         }
                         break;
                    case "ticket-created":
                         if (data.id === state.callId) {
                              cookie.set('ticket_id', data.ticket_id)
                              request.FETCH_CSAT_CONTENT(data.ticket_id)
                         }
                         break;
                    case "transfer-agent":
                         const { agent } = data
                         var liveSourceCookie = cookie.get('live-source')
                         const currentAgentId = liveSourceCookie.agent_id
                         liveSourceCookie.agent_id = agent.id

                         //if on chat, end chat
                         cookie.set('live-source', liveSourceCookie)
                         const currentUrl = window.location.href
                         window.history.pushState(null, null, currentUrl.replace(currentAgentId, agent.id));
               }
          });
     }

     const leaveCallByAgent = () => {
          removeCallId()
          sound.stopRinging();
          cookie.del("incoming-call");
          cookie.del("call_id");
          state.agora?.leaveCall()
          state.joinedCall = false;
          router.push({
               name: "product-list",
          });
     }

     const resetState = () => {
          const stateValue = state
          stateValue.agora = null
          stateValue.session = null;
          stateValue.timer = null;
          stateValue.status = null;
          stateValue.callId = null;
          stateValue.mute = false;
          stateValue.sound = sound;
          stateValue.showRating = false;
          stateValue.ringingInterval = null;
          state.joinedCall = false;
     }

     const setAgora = (agora) => {
          state.agora = agora
     }

     const setAgentId = (agentId) => {
          state.agent_id = agentId
     }
     
     const submitRating = (data) => {
          context.loading.toggle()
          return request.POST('call/rating', {
               ...data,
               ...{
                    company_id: page.company.id,
                    agent_id: state.agent_id,
                    ticket_id: cookie.get('ticket_id')
               }
          }).then((result) => {
               context.loading.toggle()
               cookie.del('ticket_id')
               state.showRating = false
               removeCallId()
               return router.push({ name: 'product-list' })
          }).catch((error) => {
               context.loading.toggle()
          })
     }

     const _testing = () => {
          setTimeout(() => {
               setCallId('99fea0b9-02e0-423f-a6a4-49b41cec4dfa')
               sound.stopRinging()
               state.agora.startDurationTiming()
          }, 1500);
     }
     return {
          inviteAgent,
          endCall,
          state,
          toggleMicrophone,
          postTriggerCall,
          setCallId,
          listenSocketBroadcastEvent,
          resetState,
          setAgora,
          submitRating,
          setAgentId
     }
}