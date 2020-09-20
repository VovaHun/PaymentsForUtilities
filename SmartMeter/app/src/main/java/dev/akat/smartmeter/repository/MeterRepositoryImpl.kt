package dev.akat.smartmeter.repository

import dev.akat.smartmeter.local.LocalDataSource
import dev.akat.smartmeter.model.User
import dev.akat.smartmeter.network.NetworkDataSource
import dev.akat.smartmeter.network.responses.*
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.withContext
import javax.inject.Inject
import javax.inject.Singleton

@Singleton
class MeterRepositoryImpl @Inject constructor(
    private val localDataSource: LocalDataSource,
    private val networkDataSource: NetworkDataSource
) : MeterRepository {

    override suspend fun authRequest(login: String, password: String) =
        withContext(Dispatchers.IO) {
            networkDataSource.authRequest(login, password)
        }

    override suspend fun updateUser(user: User): AuthResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.updateUser(user)
        }

    override fun isLoggedIn(): Boolean =
        localDataSource.isLoggedIn()

    override fun setLoggedIn(isLoggedIn: Boolean) {
        localDataSource.setLoggedIn(isLoggedIn)
    }

    override fun getAuthId(): Long =
        localDataSource.getAuthId()

    override fun setAuthId(authId: Long) {
        localDataSource.setAuthId(authId)
    }

    override suspend fun getPersonalAccounts(id: Long): AccountListResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.getPersonalAccounts(id)
        }

    override suspend fun getCompanyList(): CompanyListResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.getCompanyList()
        }

    override suspend fun getAccountInfo(id: Long, accountId: Long): AccountInfoResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.getAccountInfo(id, accountId)
        }

    override suspend fun updateDeviceIndications(deviceId: Long, indications: Double): ApiResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.updateDeviceIndications(deviceId, indications)
        }

    override suspend fun updateAccountQuery(
        id: Long,
        accountName: String,
        companyId: Long
    ): ApiResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.updateAccountQuery(id, accountName, companyId)
        }

    override suspend fun getQueryAccounts(id: Long): QueryListResponse =
        withContext(Dispatchers.IO) {
            networkDataSource.getQueryAccounts(id)
        }
}