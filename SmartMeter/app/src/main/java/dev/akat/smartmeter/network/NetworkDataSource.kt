package dev.akat.smartmeter.network

import dev.akat.smartmeter.model.User
import dev.akat.smartmeter.network.responses.*

interface NetworkDataSource {

    suspend fun authRequest(login: String, password: String): AuthResponse

    suspend fun updateUser(user: User): AuthResponse

    suspend fun getPersonalAccounts(id: Long): AccountListResponse

    suspend fun getCompanyList(): CompanyListResponse

    suspend fun getAccountInfo(id: Long, accountId: Long): AccountInfoResponse

    suspend fun updateDeviceIndications(deviceId: Long, indications: Double): ApiResponse

    suspend fun updateAccountQuery(id: Long, accountName: String, companyId: Long): ApiResponse

    suspend fun getQueryAccounts(id: Long): QueryListResponse
}
