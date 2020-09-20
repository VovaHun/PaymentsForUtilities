package dev.akat.smartmeter.repository

import dev.akat.smartmeter.model.User
import dev.akat.smartmeter.network.responses.*

interface MeterRepository {

    suspend fun authRequest(login: String, password: String): AuthResponse

    suspend fun updateUser(user: User): AuthResponse

    fun isLoggedIn(): Boolean

    fun setLoggedIn(isLoggedIn: Boolean)

    fun getAuthId(): Long

    fun setAuthId(authId: Long)

    suspend fun getPersonalAccounts(id: Long): AccountListResponse

    suspend fun getCompanyList(): CompanyListResponse

    suspend fun getAccountInfo(id: Long, accountId: Long): AccountInfoResponse

    suspend fun updateDeviceIndications(deviceId: Long, indications: Double): ApiResponse

    suspend fun updateAccountQuery(id: Long, accountName: String, companyId: Long): ApiResponse

    suspend fun getQueryAccounts(id: Long): QueryListResponse
}
