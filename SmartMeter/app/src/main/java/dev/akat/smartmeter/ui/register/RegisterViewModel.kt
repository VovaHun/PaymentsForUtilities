package dev.akat.smartmeter.ui.register

import android.content.Context
import android.util.Patterns
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.User
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import dev.akat.smartmeter.utils.Validations
import kotlinx.coroutines.launch
import javax.inject.Inject

class RegisterViewModel @Inject constructor(
    private val meterRepository: MeterRepository,
    private val appContext: Context
) :
    ViewModel() {

    private val _isFetched = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()
    private val _isFormValid = MutableLiveData<Event<Boolean>>()
    private val _formErrors = MutableLiveData<MutableList<Validations>>()
    private val _appealList = MutableLiveData<MutableList<String>>()

    val isFetched: LiveData<Event<Boolean>> get() = _isFetched
    val errorMessage: LiveData<Event<String>> get() = _errorMessage
    val isFormValid: LiveData<Event<Boolean>> get() = _isFormValid
    val formErrors: LiveData<MutableList<Validations>> get() = _formErrors
    val appealList: LiveData<MutableList<String>> get() = _appealList

    init {
        _formErrors.value = ArrayList()
        _appealList.value = ArrayList()
    }

    private var lastName = ""
    private var firstName = ""
    private var middleName = ""
    var appealType = 0
    var gender = 0
    var emailNotification = false
    var phoneNotification = false
    var comment = ""

    var username = ""
        set(value) {
            field = value
            validateForm()
        }

    var password = ""
        set(value) {
            field = value
            validateForm()
        }

    var passwordConfirm = ""
        set(value) {
            field = value
            validateForm()
        }

    var name = ""
        set(value) {
            field = value
            validateForm()
            formatAppealList()
        }

    var email = ""
        set(value) {
            field = value
            validateForm()
        }

    var phone = ""
        set(value) {
            field = value
            validateForm()
        }

    var consent = false
        set(value) {
            field = value
            validateForm()
        }

    private fun formatAppealList() {
        // get last, first and middle names
        val namesArray = name.split(" ")
        if (namesArray.isNotEmpty()) lastName = namesArray[0]
        if (namesArray.size >= 2) firstName = namesArray[1]
        if (namesArray.size >= 3) middleName = namesArray[2]

        // fill appeal list
        val list = _appealList.value!!
        list.clear()

        if (lastName.isNotEmpty() && firstName.isNotEmpty())
            list.add(appContext.getString(R.string.appeal_common_format, firstName, lastName))

        if (firstName.isNotEmpty() && middleName.isNotEmpty())
            list.add(appContext.getString(R.string.appeal_common_format, firstName, middleName))

        if (lastName.isNotEmpty())
            list.add(appContext.getString(R.string.appeal_mr_format, lastName))

        _appealList.value = list
    }

    private fun validateForm() {
        val errors = _formErrors.value!!
        errors.clear()

        if (username.isEmpty()) errors.add(Validations.EMPTY_USERNAME)
        if (password.isEmpty()) errors.add(Validations.EMPTY_PASSWORD)
        if (password != passwordConfirm) errors.add(Validations.CONFIRM_PASSWORD_ERROR)
        if (name.isEmpty()) errors.add(Validations.EMPTY_NAME)
        if (email.isNotEmpty() && !Patterns.EMAIL_ADDRESS.matcher(email).matches())
            errors.add(Validations.EMAIL_ERROR)
        if (phone.isNotEmpty() && !Patterns.PHONE.matcher(phone).matches())
            errors.add(Validations.PHONE_ERROR)
        if (!consent) errors.add(Validations.MISSING_CONSENT)

        _formErrors.value = errors
        _isFormValid.postValue(Event(errors.isNullOrEmpty()))
    }

    fun register() {
        viewModelScope.launch {
            try {
                val response = meterRepository.updateUser(
                    User(
                        username,
                        password,
                        name,
                        gender,
                        email,
                        if (emailNotification) 1 else 0,
                        phone,
                        if (phoneNotification) 1 else 0,
                        comment,
                        if (consent) 1 else 0
                    )
                )

                meterRepository.setLoggedIn(response.ok)
                response.data?.let { meterRepository.setAuthId(it.id) }

                _isFetched.postValue(Event(response.ok))
                _errorMessage.postValue(Event(response.error))
            } catch (e: Exception) {
            }
        }
    }
}