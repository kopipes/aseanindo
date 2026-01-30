import { useRequest } from "./useRequest"
import { reactive } from "vue"
import { useContext } from "./useContext.js"

const request = useRequest()
const context = useContext()
const cookie = context.cookie

export const useSchedule = () => {
     const state = reactive({
          forms: [],
          closing_statement: 'Terimakasih!',
          verification_email: true,
          bookeds : [],
          hasSubject : true
     })

     const fetchProductByCategory = (category, callback) => {
          return request
               .GET(`chat/bot/product/${category}`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const fetchLocation = (category, callback) => {
          return request
               .GET(`chat/bot-product/location/${category}`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const fetchProfessional = (category,location, callback) => {
          return request
               .GET(`chat/bot-product/professional-name/${category}?location=${location}`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const fetchPicName = (category,location,professional, callback) => {
          return request
               .GET(`chat/bot-product/pic-name/${category}?location=${location}&professional=${professional}`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const fetchScheduleDate = (category,location,professional,pic_name, callback) => {
          return request
               .GET(`chat/bot-product/schedule-date/${category}?location=${location}&professional=${professional}&pic_name=${pic_name}`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const chooseProduct = (productId,date, callback = null, errorCallback = null, params = null) => {
          return request
               .POST(
                    `chat/bot-product/choose-product`,
                    {
                         product_id: productId,
                         date : date
                    }
               )
               .then((result) => {
                    const json = result.data
                    state.forms = json.forms
                    state.verification_email = json.verification_email
                    state.closing_statement = json.closing_statement
                    state.bookeds = json.bookeds
                    state.hasSubject = json.hasSubject
                    if (callback) {
                         callback(json.forms)
                    }
               }).catch((err) => {
                    if (errorCallback) {
                         errorCallback(err.response.data)
                    }
               })
     }

     const fetchPhoneCountry = (callback) => {
          return request
               .GET(`chat/bot/phone-code`)
               .then((result) => {
                    callback(result.data)
               })
     }

     const requestOtp = (data, callback) => {
          data = {
               ...data,
               session: cookie.get('chat-bot-product-session')
          }
          return request.POST(`chat/bot-product/request-otp`, data)
               .then((result) => {
                    callback(result.data)
               })
     }

     const validateOtp = (data, success, error) => {
          data = {
               ...data,
               session: cookie.get('chat-bot-product-session')
          }
          return request.POST(`chat/bot-product/validate-otp`, data)
               .then((result) => {
                    success(result.data)
               }).catch((err) => {
                    error(err.response.data)
               })
     }

     const startSession = () => {
          const UUID = "10000000-1000-4000-8000-100000000000".replace(/[018]/g, c =>
               (+c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> +c / 4).toString(16)
          );
          cookie.set('chat-bot-product-session', UUID);
     }

     const clearCookie = (name) => {
          cookie.del(name)
     }

     const bookingTicket = (data, success, error) => {
          data = {
               ...data,
               session: cookie.get('chat-bot-product-session')
          }

          return request.POST(`chat/bot-product/booking`, data)
               .then((result) => {
                    success(result.data)
               }).catch((err) => {
                    error(err.response.data)
               })
     }

     return {
          fetchProductByCategory,
          chooseProduct,
          fetchPhoneCountry,
          requestOtp,
          validateOtp,
          startSession,
          clearCookie,
          bookingTicket,
          state,
          fetchLocation,
          fetchProfessional,
          fetchPicName,
          fetchScheduleDate
     }
}