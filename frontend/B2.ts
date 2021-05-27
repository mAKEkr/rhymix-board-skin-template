import Axios from 'axios'

class B2 {
  host: string

  constructor (host: string) {
    this.host = host
  }

  getBoardConfig (): Promise<any> {
    return this._makeRequest(
      'GET',
      ''
    )
  }

  getDocumentList () {}
  getDocumentData () {}
  getCommentList () {}
  writeDocument () {}
  writeComment () {}

  private _makeRequest (
    method: 'GET'|'POST'|'PUT'|'DELETE',
    url: string,
    params: unknown = {},
    data: unknown = {}
  ) {
    return Axios({
      method,
      baseURL: this.host,
      url,
      params,
      data
    })
  }
}

export default B2