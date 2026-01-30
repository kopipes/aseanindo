
import { sendBroadcastEvent, joinBroadcast, socket,leaveBroadcast } from "../socket"
import { useRequest } from "./useRequest"
import { useAuth } from "./useAuth.js"
import { useContext } from "./useContext.js"
import { ref, reactive } from "vue"


const request = useRequest()
const auth = useAuth()
const context = useContext()
const page = context.page
const cookie = context.cookie

export const useChat = ({ destination, agent_name, router, template, setSession }) => {
     const state = reactive({
          state: false,
          showRating: false,
          conversation: [],
          templateEndChat: template,
          chatId: null,
          user_id: auth.user_id,
          destination : destination,
          agent_name : agent_name
     })

     const setDestination = (agentId,agentName) => {
          state.destination = agentId,
          state.agent_name = agentName
     }
     const scrollToBottom = (timeout = 1) => {
          /**
           * Scrol to bottom of list conversation
           */
          setTimeout(() => {
               const box = document.querySelector(".flex-content-page-wrapper");
               if (box) {
                    box.scroll({ top: box.scrollHeight + 400, behavior: "smooth" });
               }
          }, timeout);
     }


     const setChatId = (id) => {
          if (!state.chatId && id) {
               cookie.set('chat_id', id)
               state.chatId = id
               joinBroadcast(id)
          }
     }
     const postMessage = (properties, category) => {
          /**
           * Set message properties
           */
          properties.user_id = auth.user_id
          properties.user_name = auth.name
          properties.user_profile = auth.profile
          properties.destination = state.destination
          properties.chat_id = state.chatId

          return request.POST(`chat/message/store/${category}`, properties)
     }
     const appendConversation = (message, push = true) => {
          /**
           * Get time sending
           */
          const time = new Date();
          message.time = `${time.getHours().toString().padStart(2, '0')}:${time.getMinutes().toString().padStart(2, '0')}`


          /**
           * 
           */
          message.user_id = auth.user_id
          message.name = 'You'
          message.profile = auth.profile

          if (push) {
               /**
                * Push event into destination
                */
               pushEventToDestination(message)
          }

          /**
           * Push into conversation list and scrol to bottom
           */
          state.conversation.push(message)
          scrollToBottom(0)
     }

     const pushEventToDestination = (message) => {
          /**
           * Modity message object properties
           */
          message.chat_id = state.chatId
          message.name = auth.name

          /**
           * Do Send broadcast
           */
          sendBroadcastEvent({
               clients: [state.destination],
               channel: 'new-message',
               body: message
          })
     }

     const sendLocation = () => {
          /**
           * Get location user using navigator geolocation
           */
          navigator.geolocation.getCurrentPosition((location) => {
               const { latitude, longitude } = location.coords;
               fetch(
                    `https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`
               ).then(async (result) => {
                    const json = await result.json()

                    /**
                     * Collect location information
                     */
                    const location = {
                         address: json.display_name,
                         lat: json.lat,
                         lng: json.lon
                    }

                    /**
                     * Send message
                     */
                    sendMessage(location, 'location')
               });
          },
               (error) => {
                    context.showAlert("Your computer is not allow to access your location");
               }
          );
     }
     const sendFile = (file) => {

          return postMessage({ message: file }, 'file').then((result) => {
               /**
                * Collect result and append into conversation
                */
               var data = result.data

               /**
                * Update chat id
                */
               setChatId(data.chat_id)


               delete data.chat_id
               appendConversation({
                    type: "file",
                    content: data,
                    chat_message_id :result.data.chat_message_id
               })
          })
     }

     const sendMessage = (message, type = 'message') => {
          return postMessage({ message }, type)
          .then((result) => {
               /**
                * Update chat id
                */
               setChatId(result.data.chat_id)

               /**
               * Collect result and append into conversation
               */
               appendConversation({
                    type: type,
                    content: message,
                    chat_message_id :result.data.chat_message_id
               })
          }).catch((err)=>{
               context.showAlert(err.response.data)
               setTimeout(()=>{
                    return router.push({ name: 'product-list' })
               },1000)
          })
     }

     const send = ({ message, type }) => {
          type = type || 'message'
          switch (type) {
               case 'message':
                    /**
                     * Action if type is message
                     */
                    sendMessage(message)
                    break;
               case 'location':
                    /**
                     * Action if type is location
                     */
                    sendLocation()
                    break;
               case 'file':
                    /**
                     * Action if type is file
                     */
                    sendFile(message)
                    break;
               default:
                    break;
          }
     }

     const getConversation = (callback = null) => {
          if (state.chatId) {
               return request.GET(`chat/message/${state.chatId}`)
                    .then((result) => {
                         const chat = result.data;
                         if (chat) {
                              /**
                               * Collect result data
                               */
                              var message = chat.message.map((row) => {
                                   return {
                                        ...row,
                                        profile: row.user_id ? row.profile : auth.profile,
                                        name: row.name || auth.name
                                   }
                              });
                              /**
                               * Set conversation value
                               */
                              state.conversation = message
                              if (callback) {
                                   callback()
                              }

                              /**
                               * Scroll to bottom
                               */
                              scrollToBottom()
                         }
                    })
          }
     }

     const endChat = (callbackAction = null) => {
          /**
           * Condition
           * 1. If chat or call agent already submit/create ticket (by socket event ticket-created), then show rating popup first
           * 
           * Show rating popup condiiton
           * 1. If do chat only whitout call show popup
           * 2. If chat and call but call still runing, popup not close, only end chat
           * 3. Condition for call is same
           */
          const ticketId = cookie.get('ticket_id')
          const callId = cookie.get('call_id')
          const chatId = cookie.get('chat_id')

          if (chatId) {
               var action = null
               if (callId) {
                    /**
                     * If in call do end chat and switch to call component
                     * - end chat and switch to call session
                     */
                    action = true
                    state.isDone = false
                    state.conversation = []
                    setSession('call')
               } else if (!callId) {
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
                              document.getElementById('button-show-popup-rating-chat-trigger')?.click()
                              document.getElementById('button-show-popup-rating-chat')?.click()
                         }, 200)
                    }
               }
               if (callbackAction) {
                    callbackAction()
               } else {
                    if (!action) context.loading.toggle()
                    return request.POST(`chat/message/${chatId}/${state.destination}/end`)
                         .then((result) => {
                              if (!action) context.loading.toggle()
                              if (!callId) state.isDone = true
                              state.templateEndChat = cookie.get('template_end_chat')
                              cookie.del('chat_id')
                              state.chatId = null
                              leaveBroadcast(chatId)
                         })
                         .catch((error) => {
                              cookie.del('chat_id')
                              console.log(error)
                         });
               }

          } else if (callId) {
               setSession('call')
          } else {
               /**Nothing active session (not in call and chat) just switch page to home list product*/
               return router.push({ name: 'product-list' })
          }

     }

     const submitRating = (data) => {
          context.loading.toggle()
          return request.POST('call/rating', {
               ...data,
               ...{
                    company_id: page.company.id,
                    agent_id: state.destination,
                    ticket_id: cookie.get('ticket_id')
               }
          }).then((result) => {
               context.loading.toggle()
               cookie.del('ticket_id')
               if (data.source === 'call') {
                    return router.push({ name: 'product-list' })
               }
          }).catch((error) => {
               context.loading.toggle()
          })
     }

     const chatEndByAgent = () => {
          if (cookie.get('call_id')) {
               state.isDone = false
               state.conversation = []
               setSession('call')
          } else {
               state.isDone = true
          }
          state.templateEndChat = cookie.get('template_end_chat')
          cookie.del('chat_id')
          state.chatId = null
     }

     const listenSocketBroadcastEvent = () => {
          // socket.off('BROADCAST')
          socket.on('BROADCAST', (properties) => {
               const { channel, data } = properties
               switch (channel) {
                    case "new-message":
                         if (data.chat_id === state.chatId) {
                              state.conversation.push(data)
                              scrollToBottom()
                         }
                         break;
                    case "chat-ended":
                         if (data.chat_id === state.chatId) {
                              endChat(() => {
                                   chatEndByAgent()
                              })
                         }
                         break;
                    case "ticket-created":
                         if (data.id === state.chatId) {
                              cookie.set('ticket_id', data.ticket_id)
                              request.FETCH_CSAT_CONTENT(data.ticket_id)
                         }
                         break;
               }
          });
     }

     const transferChat = (data) =>{
          const { agent,category } = data
          var liveSourceCookie = cookie.get('live-source')
          const currentAgentId = liveSourceCookie.agent_id
          liveSourceCookie.agent_id = agent.id

          cookie.set('live-source', liveSourceCookie)
          const currentUrl = window.location.href
          const currentAgentName = state.agent_name
          window.history.pushState(null, null, currentUrl.replace(currentAgentId, agent.id));
          if(category==='chat'){
               getConversation()
          }else{
               endChat(() => {
                    chatEndByAgent()
               })
          }
     }

     const startSession = () => {
          setChatId(cookie.get('chat_id'))
          getConversation()
          // listenSocketBroadcastEvent()
     }
     return {
          send,
          getConversation,
          startSession,
          endChat,
          state,
          submitRating,
          setDestination,
          transferChat,
          listenSocketBroadcastEvent
     }
}
