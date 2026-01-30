import { useRequest } from "./useRequest"
import { useContext } from "./useContext.js"
import { reactive } from "vue"

const request = useRequest()
const context = useContext()
const cookie = context.cookie

const chatBotSessionCookieName = 'bbt-ssn'
export const useChatBot = () => {

     const timeoutFetchNextMessage = 3000
     const state = reactive({
          conversation: [],
          thinking: false
     })
     const startSession = (properties) => {
          if (!cookie.get('chat_bot_session')) {
               cookie.del(`otp-interval-second-verification-bot-email`);
               const hasStart = cookie.getWithValidate(chatBotSessionCookieName)
               return request
                    .POST(`chat/bot/start`,{
                         session : hasStart
                    })
                    .then((result) => {
                         if(!hasStart){
                              cookie.setWithExpired(chatBotSessionCookieName,true)
                         }
                         const { session_id, message, type } = result.data
                         pushConversation(message)
                         cookie.set('chat_bot_session', session_id)
                         if (type === 'message') {
                              setTimeout(() => {
                                   postNextMessage()
                              }, 1000)
                         }
                    })
                    .catch((err)=>{
                         if(properties.errorCallback){
                              properties.errorCallback()
                         }
                    })
          } else {
               return fetchConversation()
          }
     }

     const postNextMessage = () => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               return request
                    .POST(`chat/bot/${sessionId}/next`)
                    .then((result) => {
                         const { message, type } = result.data
                         if (type === 'message_helpdesk') {
                              var param = []
                              const appUrl = context.page.app_url;
                              for (var row of message.message.properties.helpdesk) {
                                   param.push(`id[]=${row.id}`)
                              }
                              pushLiveAgentChat(`${appUrl}/helpdesk?${param.join('&')}`)

                         } else {
                              pushConversation(message)
                         }
                         if (type === 'message') {
                              setTimeout(() => {
                                   postNextMessage()
                              }, timeoutFetchNextMessage)
                         }
                    })
          }
     }

     const fetchConversation = () => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               return request
                    .GET(`chat/bot/${sessionId}/conversation`)
                    .then((result) => {
                         state.conversation = result.data
                         scrollToBottom(0)
                    })

          }
     }


     const userSendMessage = (userMessage, userMessageType = 'message', callback = null, errorCallback = null,params = null) => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               return request
                    .POST(
                         `chat/bot/${sessionId}/send-message`,
                         {
                              message: userMessage,
                              type: userMessageType,
                              ...params
                         }
                    )
                    .then((result) => {
                         const { message, next, type } = result.data
                         if (callback) {
                              callback()
                         }
                         pushConversation(message)
                         if (next) {
                              setTimeout(() => {
                                   pushConversation(next)
                                   botResponseMessage(userMessage, userMessageType,null,null,params)
                              }, 1000)
                         } else {
                              botResponseMessage(userMessage, userMessageType,null,null,params)
                         }
                    }).catch((err) => {
                         if (errorCallback) {
                              errorCallback(err.response.data)
                         }
                    })

          }
     }

     const botResponseMessage = (userMessage, userMessageType, callback = null, errorCallback = null,params = null) => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               if (!callback) {
                    state.thinking = true
               }
               return request
                    .POST(
                         `chat/bot/${sessionId}/response-message`,
                         {
                              message: userMessage,
                              type: userMessageType,
                              ...params
                         }
                    )
                    .then((result) => {
                         const { message, next, type } = result.data
                         if (callback) {
                              callback()
                         }
                         state.thinking = false
                         if (message.message_type === "bot_message_helpdesk") {
                              var param = []
                              const appUrl = context.page.app_url;
                              for (var row of message.message.properties.helpdesk) {
                                   param.push(`id[]=${row.id}`)
                              }
                              pushLiveAgentChat(`${appUrl}/helpdesk?${param.join('&')}`)

                         }
                         if (message) {
                              if (message.message_type !== 'bot_message_helpdesk') {
                                   pushConversation(message)
                              }
                              if (type === 'message') {
                                   setTimeout(() => {
                                        postNextMessage()
                                   }, 1000)
                              }
                              if (next) {
                                   setTimeout(() => {
                                        if(next.message_type!=='bot_message_helpdesk'){
                                             pushConversation(next)
                                             if(next.message_type==='bot_message'){
                                                  setTimeout(() => {
                                                       postNextMessage()
                                                  }, 1000)
                                             }
                                        }else{
                                             var param = []
                                             const appUrl = context.page.app_url;
                                             for (var row of next.message.properties.helpdesk) {
                                                  param.push(`id[]=${row.id}`)
                                             }
                                             pushLiveAgentChat(`${appUrl}/helpdesk?${param.join('&')}`)
                                        }
                                   }, 1000)
                              }
                         }
                    }).catch((err) => {
                         if (errorCallback) {
                              errorCallback(err.response.data)
                         }
                    })

          }
     }

     const resendBookingOtp = (callback) => {
          const sessionId = cookie.get('chat_bot_session');
          return request
               .POST(
                    `chat/bot/${sessionId}/resend-otp`
               ).then((result) => {
                    callback(result.data)
               })
     }

     const validateBookingOtp = (props) => {
          const { otp, success, error } = props
          const sessionId = cookie.get('chat_bot_session');
          return request
               .POST(
                    `chat/bot/${sessionId}/validate-otp`,
                    {
                         otp: otp
                    }
               ).then((result) => {
                    const { message, next, type } = result.data
                    if (message) {
                         pushConversation(message)
                         if (next.message_type !== 'bot_message_helpdesk') {
                              pushConversation(next)
                         } else {
                              var param = []
                              const appUrl = context.page.app_url;
                              for (var row of next.message.properties.helpdesk) {
                                   param.push(`id[]=${row.id}`)
                              }
                              pushLiveAgentChat(`${appUrl}/helpdesk?${param.join('&')}`)
                         }
                    }
                    success(result.data)
               }).catch((err) => {
                    error(err.response.data)
               })
     }

     const endBotSession = (callback) => {
          const sessionId = cookie.get('chat_bot_session');
          if(sessionId){
               return request
                    .POST(
                         `chat/bot/${sessionId}/end`,
                    ).then((result) => {
                         cookie.del('chat_bot_session')
                         callback()
                    }).catch((err) => {
                    })
          }else{
               callback()
          }
     }

     const fetchProductList = (callback) => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               return request
                    .GET(`chat/bot/${sessionId}/product`)
                    .then((result) => {
                         callback(result.data)
                    })

          }
     }

     const fetchPhoneCountry = (callback) => {
          const sessionId = cookie.get('chat_bot_session');
          if (sessionId) {
               return request
                    .GET(`chat/bot/phone-code`)
                    .then((result) => {
                         callback(result.data)
                    })

          }
     }

     const pushConversation = (message) => {
          state.conversation.push(message)
          scrollToBottom(1)
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

     const pushLiveAgentChat = (url) => {
          const date = new Date()
          var hour = date.getHours()
          var minute = date.getMinutes()
          hour = hour.toString().padStart(2,'0')
          minute = minute.toString().padStart(2,'0')
          const message = {
               message : 'Silahkan klik button dibawah ini:',
               message_type : 'message_link',
               sender : 'bot',
               time : `${hour}:${minute}`,
               options : [{
                    label : 'Live Agent',
                    link : url
               }]
          };
          pushConversation(message)          
     }

     return {
          startSession,
          userSendMessage,
          fetchProductList,
          botResponseMessage,
          fetchPhoneCountry,
          resendBookingOtp,
          validateBookingOtp,
          endBotSession,
          postNextMessage,
          state,
     }
}