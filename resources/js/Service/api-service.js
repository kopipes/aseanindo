import { useContext } from "../Hooks/useContext.js"
import { useRequest } from "../Hooks/useRequest.js"

const request = useRequest()
const context = useContext()

export const api = {
     getListFaq: (setData) => {
          const url = 'helpdesk/faq'
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },
     getTopFaq: (setData) => {
          const url = 'helpdesk/faq/top'
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },
     getDetailFaq: (id,setData) => {
          const url = `helpdesk/faq/${id}`
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },
     getListProduct: (setData) => {
          const url = 'helpdesk/product'
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },

     getListHelpdesk: (setData) => {
          const url = 'helpdesk/helpdesk-category'
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },

     getListHelpdeskById: (id,setData) => {
          const url = `helpdesk/helpdesk-category?id[]=${id.join('&id[]=')}`
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData([])
               })
     },

     getListAgentHelpdesk: (params, setData) => {
          const url = `helpdesk/agent/${params.id}/${params.category}`
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData({
                         name: "",
                         agent: []
                    })
               })
     },

     
     findRandomAvailableAgent: (params, setData) => {
          const url = `helpdesk/agent/available/${params.id}/${params.category}`
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData({
                         name: "",
                         agent: []
                    })
               })
     },

     getListHelpdeskId: (params) => {
          const url = `helpdesk/helpdesk-list/${params.id}/${params.category}`
          return request.GET(url)
     },

     getDetailAgent: (id, setData) => {
          const url = `helpdesk/agent/${id}`
          return request.GET(url)
               .then((result) => {
                    setData(result.data)
               })
               .catch((error) => {
                    setData(null)
               })
     },

     postRequestCallCallback: (data, callback = null) => {
          const now = new Date()
          var totalRequest = 0;
          var cookieRequest = context.cookie.get('callback-request');
          if (cookieRequest) {
               if (now > Date.parse(cookieRequest.expiry)) {
                    context.cookie.del('callback-request')
               } else {
                    totalRequest = cookieRequest.value
               }
          }
          if (totalRequest < 3) {
               return request.POST(`call/request-callback`, {
                    helpdesk_category_id: data.helpdesk_id,
                    user_id : data.user_id,
                    product_id : data.product_id,
                    note : data.note,
                    name : data.name,
                    email : data.email,
                    phone_code : data.phone_code,
                    phone : data.phone,
               }).then((result) => {
                    context.cookie.set('callback-request', {
                         value: totalRequest + 1,
                         expiry: now.setSeconds(now.getSeconds() + 3600),
                    })

                    if (callback) {
                         callback()
                    }
                    setTimeout(() => {
                         context.showAlert('Thank you! <br>Our team will contact you soon during office hours.')
                    }, 500)
               })
          }
     }
}