package dev.akat.smartmeter.network

import dev.akat.smartmeter.model.User
import dev.akat.smartmeter.network.responses.*
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class NetworkDataSourceImpl @Inject constructor(private val apiService: ApiService) :
    NetworkDataSource {

    override suspend fun authRequest(login: String, password: String): AuthResponse =
        apiService.authRequest(login, password)

    override suspend fun updateUser(user: User): AuthResponse =
        apiService.updateUser(
            user.login,
            user.password,
            user.name,
            user.gender,
            user.email,
            user.emailNotification,
            user.phone,
            user.phoneNotification,
            user.comment,
            user.consent
        )

    override suspend fun getPersonalAccounts(id: Long): AccountListResponse =
        apiService.getPersonalAccounts(id)

    override suspend fun getCompanyList(): CompanyListResponse =
        apiService.getCompanyList()

    override suspend fun getAccountInfo(id: Long, accountId: Long): AccountInfoResponse =
        apiService.getAccountInfo(id, accountId)

    override suspend fun updateDeviceIndications(deviceId: Long, indications: Double): ApiResponse =
        apiService.updateDeviceIndications(deviceId, indications)

    override suspend fun updateAccountQuery(
        id: Long,
        accountName: String,
        companyId: Long
    ): ApiResponse = apiService.updateAccountQuery(id, accountName, companyId)

    override suspend fun getQueryAccounts(id: Long): QueryListResponse =
        apiService.getQueryAccounts(id)
}